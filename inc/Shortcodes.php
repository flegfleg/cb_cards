<?php
/*
* Aktuelle Shortcodes:
* [cb_postgrid itemcat,locationcat,hidedefault=False]
*
*/


function shortcode_postGridfromCategory($atts){
	
	require_once(CB_CARDS_PLUGIN_PATH . '/inc/View/postGrid.php');
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
		renderFilterNav();
	}
	
	$atts['hidedefault'] = filter_var( $atts['hidedefault'], FILTER_VALIDATE_BOOLEAN );
	$atts['sortbyavailability'] = filter_var( $atts['sortbyavailability'], FILTER_VALIDATE_BOOLEAN);

  $itemList = get_cb_items_by_category($itemcat);

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
		return __('no posts found', 'cb_cards');
	}
}

add_shortcode( 'cb_postgrid', 'shortcode_postGridfromCategory' );



function shortcode_locationCats($atts){
	$atts = shortcode_atts( array(
		'itemcat' => '',
	),$atts);
	$html = '';
	$itemcat_url = '';
	$itemTerms = get_terms(array('taxonomy' => 'cb_locations_category'));
	if ($atts['itemcat'] != ''){
		$itemterm = get_term_by('slug',$atts['itemcat'],'cb_items_category');
		$itemterm_id = $itemterm -> term_id;
		$itemcat_url = '?itemcat=' . $itemterm_id;

		foreach ($itemTerms as $key => $term){
			$itemsForTerm = get_cb_items_by_category($atts['itemcat']); //nimmt alle buchbaren Items der entsprechenden Kategorie
			$itemsForTerm = filterPostsByLocation($itemsForTerm,$term->slug); //entfernt alle Items, die nicht in der Location sind

			if (!$itemsForTerm) { //entfernt alle Terms die nicht items der entsprechenden Kategorie haben
				unset($itemTerms[$key]);
			}

		}
	}

	foreach ($itemTerms as $key => $term) {
		$html .= '<a href="'.esc_url( get_term_link( $term ) . $itemcat_url ).'">' . $term->name . '</a>';
		if ($key != array_key_last($itemTerms)) {
			$html .= ', '; //adds seperator when not last item
		}
	}
	return $html;
}

add_shortcode( 'cb_locationcats', 'shortcode_locationCats' );


function renderFilterNav( ) {
	
	$include_empty = TRUE;
	$hide_unavailable_items = FALSE;
	$hide_empty_cats = TRUE;
	$taxonomy = 'cb_items_category';
	$css_class = '';
	
	// Get the taxonomy's terms
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
		)
	);

	if ( ! empty( $terms ) && is_array( $terms ) ) { ?>
		
		<ul class="<?php echo $css_class; ?> nav-ul builder-item--primary-menu">
				<li class="term-all"><a href="<?php echo esc_url( add_query_arg( 'itemcat','' ) ) ?>" >
				<?php echo __(
					'All', 'cb_cards'
				); ?>
			</a></li>
		<?php foreach ( $terms as $term ) {			
			$args = array(
				'orderby'			=> 'title',
				'order'				=> 'ASC',
				'category_slug' => $term->slug,
			);
			$items = \CommonsBooking\Repository\Item::get($args, $hide_unavailable_items);
			$count = count($items);
		    ?>
				<li class="menu-item term-<?php echo $term->slug; ?>"><a href="<?php echo esc_url( add_query_arg( 'itemcat', $term->slug ) ) ?>" >
					<?php echo $term->name; ?>
				(<?php echo $count; ?>)</a></li><?php
			} ?>
		</ul>
	<?php 
	} // end if  ! empty( $terms ) && is_array( $terms )
} // end func
?>
