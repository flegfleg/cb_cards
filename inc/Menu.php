<?php //add custom menu.php entry 
add_action('admin_init', 'menu_initialize_theme_options'); 

function menu_initialize_theme_options() {  
	add_settings_section(  
		'menu_settings_section',
		'menu Options',                  
		'menu_general_options_callback',
		'nav-menus.php'                            
	);  

	add_settings_field(  
		'test_field',                        
		'Test',                             
		'menu_test_field_callback',  
		'nav-menus.php',                            
		'menu_settings_section',         
		array(                             
			'Activate this setting to TEST.'  
		)  
	);

	register_setting(  
		'nav-menus.php',  
		'test_field'  
	);
	
	add_meta_box( 'metabox-id', 'metabox-title', 'menu_test_field_callback', 'nav-menus', 'side', 'low' );

}

function menu_test_field_callback($args) {  
	$html = '<input type="checkbox" id="test_field" name="test_field" value="1" ' . checked(1, get_option('test_field'), false) . '/>';
	$html .= '<label for="test_field"> '  . $args[0] . '</label>';  
   echo $html;  
}