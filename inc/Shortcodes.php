<?php
/*
* Aktuelle Shortcodes:
* [cb_catalogue]
*
*/

function shortcode_cb_catalogue($atts){
	
	require_once(CB_CATALOGUE_PLUGIN_PATH . '/inc/View/postGrid.php');
	
	$atts = shortcode_atts( array(
		'itemcat' => '',
	    'locationcat' => '',
		'class' => '',
		'hidedefault' => TRUE,
    	'sortbyavailability' => TRUE,
		'include_filter' => 'true' // Show the filter Nav HTML
	),$atts);
	
	$atts['itemcat'] = filter_var( $atts['itemcat'], FILTER_VALIDATE_INT );
	
	// allow setting itemcat by url 
	$itemcat = get_query_var( 'itemcat', $atts['itemcat'] );	
	
	$atts['include_filter'] = filter_var( $atts['include_filter'], FILTER_VALIDATE_BOOLEAN );
	if ($atts['include_filter']) {
		
	}
	
	$itemList = filterNavBar( 'cb_items_category', $itemcat );

	$atts['hidedefault'] = filter_var( $atts['hidedefault'], FILTER_VALIDATE_BOOLEAN );
	$atts['sortbyavailability'] = filter_var( $atts['sortbyavailability'], FILTER_VALIDATE_BOOLEAN);

  // $itemList = get_cb_items_by_category($itemcat, FALSE);

  if ($atts['locationcat'] != '') {
    $itemList = filterPostsByLocation($itemList,$atts['locationcat']);
  }
  $itemAvailabilities = itemListAvailabilities($itemList);
  
  if ($atts['sortbyavailability']){
    $itemList = sortItemsByAvailability($itemList,$itemAvailabilities);
  }

	if ($itemList){
		return create_postgrid_from_posts($itemList,$itemAvailabilities,$atts['hidedefault'],$atts['class']);
	}
	else {
		return '<div class="cb-notice cb-error">' . __('No posts found', 'cb_cards') . '</div>';
	}
}

add_shortcode( 'cb_catalogue', 'shortcode_cb_catalogue' );


function filterNavBar( $taxonomy = 'cb_items_category', $current_term = '' ) {
	
	$include_empty = TRUE;
	$hide_unavailable_items = FALSE;
	$hide_empty_cats = TRUE;
	$css_class = '';
	$current_term_items = array();
	
	// Get the taxonomy's terms
	$nav_terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
		)
	);
	
	// add a dummy entry for "all/no-filter"
	$nav_no_filter = array(
		'name' => __('All', 'cb_cards'),
		'slug' => '' 
		);
		
	$nav_obj = (object) $nav_no_filter;
	array_unshift($nav_terms,  $nav_obj);
		
	if ( ! empty( $nav_terms ) && is_array( $nav_terms ) ) { 
		$term_items = array();
		?>

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
				$current_term_items = $term_items;
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
	return $current_term_items;
	} // end if  ! empty( $terms ) && is_array( $terms )
}
?>
