<?php

// 1)

$my_option_name = "_edit_last"; // test data

global   $wpdb;

// I would probably specify the output type, e.g. ARRAY_A. Additionally, a prepared statement with $wpdb->prepare() does not seem required.
$results   =   $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE meta_key = '{$my_option_name}' " , ARRAY_A  ); 

echo "<pre>"; 
print_r( $results );
echo "</pre>"; 




// 2)

$your_name = 'Benjamin" ?>'; // test data with problematic characters
// $your_name = ""; // test data

?>

<!--   Assume   $your_name   holds   the   previously   submitted   answer   (if   any),   or empty   string   -->
<label>
<span><?php   _e('Your   name',   'test');   ?></span> <!--   error in php syntax: missing ">"   -->
<input   type="text"   name="your_name"   value="<?php  echo esc_attr( $your_name );   ?>"   /> <!--   I would escape the output   -->
</label>

<?php



// 3).   
// Prepare   the   following   code   for   localization,   as   best   and concise   as   you   can.   Use   'test'   as   your   translation   domain:

$apples   =   rand(0,5);

printf( __( '2: We have %d apples!', 'test' ), $apples ); // alternative 1
echo   "1: We   have   " . __( $apples,   'test') . " apples!"; // alternative 2: may also work, but 1 is better




// 4).   Nothing   is   ever   perfect,   right?
// Tell   us   what   we’re   doing   in   this   code,   and   how   can   it   be   improved?
// Assume   user   privileges   and   nonce   are   correctly   checked

$_POST['option1'] = "test2"; // test data

$option_key = 'my_option_key';

$options   =   array();
$options['option1']   =   isset(   $_POST['option1']   )   ?   sanitize_option( $option_key, $_POST['option1'] )  :   ''; 
update_option( $option_key ,   $options);

// You are reading a form via POST. 
// You check if the value of option 1 is set. If yes, you assign the value of $_POST['option1'] to the array $options.
// Then, you add or update an entry (an array) in the options table
// Improvements: It might not be necessary to have options as an array. 
// Also, it could be beneficial to santize $_POST['option1'] with sanitize_option() or sanitize_text_field()


// 5).   Can   you   think   of   a   scenario   where   code   such   as   this   would be   an   anti-pattern?
//  file:   uninstall.php 

// My answer: I am not sure because I did not work with multisites so far. 
// If it's a network of sites, the blog_id is still a unique identifier. 
// So there is no problem with a blog_id value duplicate.
// Maybe it is useful to check if option_name is present before deleting it

if   (   is_multisite()   )   {

	global $wpdb;

	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );

	if   ( !empty( $blogs ) ) {

		foreach ( $blogs as $blog ) {

			switch_to_blog( $blog['blog_id'] );
			delete_option( 'option_name' ); 

		} 
	}

} else {
	
	delete_option(   'option_name'   ); 
}	


// 6).   Can   you   explain   what   might   go   wrong   with   this   bit   of javascript   code?

if   ("object"   ===   typeof   a   &&   "stuff"   in   a)   {   
	alert(a.stuff);   
}

// If the object has a default value set via prototype, you can delete the stuff property but still might jump into the if statement.
// This behavior might not be wanted in this case.
// The code below shows this scenario

Stuffobject = function(stuffvalue) {
    
    if(typeof stuffvalue !== "undefined") {
    	this.stuff = stuffvalue;
    }
    
}

Stuffobject.prototype.stuff = 'default stuff value';

var a = new Stuffobject("stuffvalue1");

delete a.stuff;

if   ( "object" === typeof a && "stuff" in a) {   

	alert( a.stuff );

}



// 7).   Can   you   spot   a   problem   with   this   bit   of   javascript   code?
//   file:   my-js-file.js

function get_number(string) {
	return parseInt(string); 
}

// I would check if the string is a valid to parse and otherwise return 0 (other then NaN).

function get_number(string) {

 	//	return parseInt(string); 
    return ( parseInt(string) ? parseInt(string) : 0 );
    
}


// 8).   Can   you   spot   a   problem   with   this   bit   of   code?
// <?php

update_option( "my_plugin_option" ,   "0"); // test data

function is_not_ready_to_go(){
	
	return empty( get_site_option( 'my_plugin_option' ) );

}

var_dump( is_not_ready_to_go() );

// If the value of my_plugin_option is e.g. "0" , the function returns true meaning the plugin is not ready to go.
// But "0" could be a a valid option value of te plugin. 



// 9).   How   would   you   refactor   this   bit   of   code   to   be   more   concise   in WordPress   context?
// <?php
// Assume   $map   looks   like   this: 
 $map   =   array(
	array(   'name'   =>   'Name 1',   'something   else'   =>   'whatever'   ),
	array(   'name'   =>   'Name 2',   'something   else'   =>   'whatever'   ),
	array(   'name'   =>   'Name 3',   'something   else'   =>   'whatever'   ),
	array(   'name'   =>   'Name 4',   'something   else'   =>   'whatever'   ),
	array(   'name'   =>   'Name 5',   'something   else'   =>   'whatever'   ),
	array(   'name'   =>   'Name 6',   'something   else'   =>   'whatever'   ),
	array(   'name'   =>   'Name 7',   'something   else'   =>   'whatever'   ),
	array(   'name'   =>   'Name 8',   'something   else'   =>   'whatever'   ),
	array(   'name'   =>   'Name 9',   'something   else'   =>   'whatever'   )
  );

$names   =   array();

foreach ( $map as $data ) {
	$names[] = $data['name']; 
}


// A refactored version can be found below

$map2 = array();

for ($i=0; $i < 10 ; $i++) { 
	$map2[$i] = array( 'name' => 'Name {$i}', 'something else' => 'whatever' );
}

$names2 = array_map( function ( $map2 ) { return $map2["name"]; } , $map2 ); 



// 10).   How   would   you   refactor   this   bit   of   code   in   a   plugin   you're assigned?
//   Assume   $path_piece1   =   '/test/dir1/';
//   Assume   $path_piece2   =   'subdir2\file.txt';

$path_piece1   =   '/test/dir1/';
$path_piece2   =   'subdir2\file.txt';

// $path = str_replace( '\\', '/', $path_piece1 . $path_piece2 ); // the file is not accessible like this
$path = plugins_url( str_replace( '\\', '/', $path_piece1 . $path_piece2 ) , __FILE__ ); // refactored, so file can be accessed


// 11).   Is   there   anything   you   would   improve   in   this   piece   of code?
// Assume   js/my-custom-posts-script.js   is   present,   and   does   something   on posts   list   page   (edit.php)


// I would load the script only if it's needed. The script below is only loaded on the pages and posts overview in the admin area

function   my_admin_scripts_inclusion_proc( $hook )   {

	if ( $hook == 'edit.php' ) {  
        wp_enqueue_script(   'my-custom-posts-script',   plugins_url( 'js/my-custom-posts-script.js',   __FILE__   )   );
    }
}

add_action('admin_enqueue_scripts','my_admin_scripts_inclusion_proc', 10, 1 );

// To make sure it's only on the blog posts overview page, I would exclude the page with the following parameter: post_type=page 
// The page could further be identified with: get_current_screen()->base;


