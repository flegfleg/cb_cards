<?php
/*
* Aktuelle Shortcodes:
* [cb_catalogue]
*
*/

function shortcode_cb_catalogue($atts){
	
	$atts = shortcode_atts( array(
		'itemcat' => '',
		'class' => '',
		'hidedefault' => TRUE,
		'layout' => 'masonry',
    	'sortbyavailability' => TRUE
	),$atts);
	
	$atts['itemcat'] = filter_var( $atts['itemcat'], FILTER_VALIDATE_INT );
	$atts['hidedefault'] = filter_var( $atts['hidedefault'], FILTER_VALIDATE_BOOLEAN );
	$atts['sortbyavailability'] = filter_var( $atts['sortbyavailability'], FILTER_VALIDATE_BOOLEAN);
	
	$itemcat = cb_catalogue_get_current_term( $atts['itemcat'] ); // allow setting itemcat by url 

	cb_catalogue_render_filternav( $itemcat );
	
	// filter the item list 
	$itemList = cb_catalogue_get_items( $itemcat );
  	$itemAvailabilities = itemListAvailabilities($itemList);
  	if ($atts['sortbyavailability']){
    	$itemList = sortItemsByAvailability($itemList,$itemAvailabilities);
  	}
	
	if ($itemList){	
		cb_catalogue_render_basic_grid( $itemList,$itemAvailabilities,$atts['hidedefault'],$atts['class'] );
	}
	else {
		return '<div class="cb-notice cb-error">' . __('No posts found', 'cb_cards') . '</div>';
	}
}

add_shortcode( 'cb_catalogue', 'shortcode_cb_catalogue' );



function cb_catalogue_get_current_term( $default ) {
	return get_query_var( 'itemcat', $default ); // allow setting itemcat by url 
}

 function cb_catalogue_render_masonry_grid( $itemList,$itemAvailabilities,$hidedefault,$class ) {
	require_once(CB_CATALOGUE_PLUGIN_PATH . '/inc/View/masonryGrid.php');
	echo create_postgrid_from_posts($itemList,$itemAvailabilities,$hidedefault,$class);
 } 
 
 function cb_catalogue_render_basic_grid( $itemList,$itemAvailabilities,$hidedefault,$class ) {
	require_once(CB_CATALOGUE_PLUGIN_PATH . '/inc/View/basicGrid.php');
	echo create_basic_grid_from_posts($itemList,$itemAvailabilities,$hidedefault,$class);
 }
 

function cb_catalogue_get_items( $current_term = '' ) {
	
	$args = array(
		'orderby'			=> 'title',
		'order'				=> 'ASC',
		'category_slug' => $current_term,
	);
	$term_items_filtered = \CommonsBooking\Repository\Item::get($args, FALSE);
	
	return $term_items_filtered;
}


function cb_catalogue_render_filternav( $current_term = '') {
	
	$include_empty = TRUE; 			// include empty categories 
	$include_filter_all = TRUE; 			// include "all" (unfiltered)
	$hide_unavailable_items = FALSE;
	$taxonomy = 'cb_items_category';
	$css_class = '';
		
	$current_term_items = array(); 	// only items matching current term
	$nav_list_items		= array();	// navigation items
	
	
	// Get the taxonomy's terms
	$nav_terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
		)
	);
	
	if ($include_filter_all) {	
		// add a dummy entry for "all/no-filter"
		$nav_no_filter = array(
			'name' => __('All', 'cb_catalogue'),
			'slug' => '' 
			);
			
		$nav_obj = (object) $nav_no_filter;
		array_unshift($nav_terms,  $nav_obj); // insert "all" entry as first		
	}
		
	if ( ! empty( $nav_terms ) && is_array( $nav_terms ) ) { ?>
		<div class="cb-wrapper">
			<ul class="<?php echo $css_class; ?> filterbar">
				<?php foreach ( $nav_terms as $term ) {			
					$args = array(
						'orderby'			=> 'title',
						'order'				=> 'ASC',
						'category_slug' => $term->slug,
					);
					$term_items = \CommonsBooking\Repository\Item::get($args, $hide_unavailable_items);
					$count = count($term_items);
		
					$is_current = '';
					
					if ( $term->slug == $current_term ) {
						$is_current = 'current-term';
					}
					?>
						<li class="term-<?php echo $term->slug; ?> <?php echo $is_current; ?>"><a href="<?php echo esc_url( add_query_arg( 'itemcat', $term->slug ) ) ?>" >
							<?php echo $term->name; ?>
						</a>(<?php echo $count; ?>)</li><?php
				} ?>
			</ul>
		</div>
	<?php
	} // end if  ! empty( $terms ) && is_array( $terms )
}



?>
