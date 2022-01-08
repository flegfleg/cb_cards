<?php
/*
* Aktuelle Shortcodes:
* [cb_postgrid itemcat,locationcat,hidedefault=False]
*
* [cb_itemgallery itemcat,locationcat,hideDefault=True]
*
*/


function shortcode_postGridfromCategory($atts){
	$atts = shortcode_atts( array(
		'itemcat' => '',
	  'locationcat' => '',
		'class' => '',
		'hidedefault' => 'false',
    'sortbyavailability' => 'true',
		'kupplung' => ''
	),$atts);
	$atts['hidedefault'] = filter_var( $atts['hidedefault'], FILTER_VALIDATE_BOOLEAN );
  $atts['sortbyavailability'] = filter_var( $atts['sortbyavailability'], FILTER_VALIDATE_BOOLEAN);


	/* Mies dreckiger Workaround bis es die Funktion filterPostsByKupplung gibt*/
	if ($atts['kupplung'] != '') {
		$itemList = get_post_by_category_and_kupplung($atts['itemcat'],array($atts['kupplung']));
	}
	else {
		$itemList = get_cb_items_by_category($atts['itemcat']);
	}

  if ($atts['locationcat'] != '') {
    $itemList = filterPostsByLocation($itemList,$atts['locationcat']);
  }

  if ($atts['sortbyavailability']){
    $itemList = sortItemsByAvailability($itemList);
  }


	if ($itemList){
		return create_postgrid_from_posts($itemList,$atts['hidedefault'],$atts['class']);
	}
	else {
		return "no posts found";
	}
}

add_shortcode( 'cb_postgrid', 'shortcode_postGridfromCategory' );

$galleryIterator = 0;

function shortcode_itemGalleryfromCategory($atts){
	global $galleryIterator;
	$atts = shortcode_atts( array(
		'itemcat' => '',
	  'locationcat' => '',
		'class' => '',
		'hidedefault' => 'true',
    'sortbyavailability' => 'true'
	),$atts);
	$atts['hidedefault'] = filter_var( $atts['hidedefault'], FILTER_VALIDATE_BOOLEAN );
  $atts['sortbyavailability'] = filter_var( $atts['sortbyavailability'], FILTER_VALIDATE_BOOLEAN);

	$itemList = get_cb_items_by_category($atts['itemcat']);

  if ($atts['locationcat'] != '') {
    $itemList = filterPostsByLocation($itemList,$atts['locationcat']);
  }

  if ($atts['sortbyavailability']){
    $itemList = sortItemsByAvailability($itemList);
  }

	if ($itemList){
		$gallery_html = cb_itemGallery($itemList,$galleryIterator,$atts['hidedefault'],$atts['class']);
		$galleryIterator = $galleryIterator + 1;
		?>
		<script>
		var galleryIterator = <?php echo json_encode($galleryIterator); ?>;
		</script>
		<?php
		return $gallery_html;
	}
	else {
		return "no posts found";
	}
}

add_shortcode( 'cb_itemgallery', 'shortcode_itemGalleryfromCategory' );

function shortcode_locationCats($atts){
	$atts = shortcode_atts( array(
		'itemcat' => '',
	),$atts);
	$html = '';
	$itemcat_url = '';
	$itemTerms = get_terms(array('taxonomy' => 'cb_locations_category'));
	if ($atts['itemcat'] != ''){
		$itemterm = get_term_by('slug',$atts['itemcat'],'cb_items_category');
		$itemterm_id = $itemterm -> ID;
		$itemcat_url = '?itemcat=' . $itemterm_id;

		foreach ($itemTerms as $key => $term){
			$itemsForTerm = get_cb_items_by_category($atts['itemcat']); //nimmt alle buchbaren Items der entsprechenden Kategorie
			$itemsForTerm = filterPostsByLocation($itemsForTerm,$term->slug); //entfernt alle Items, die nicht in der Location sind
			if ($itemsForTerm) { //Nur wenn in der Location items der angegebenen Kategorie sind wird diese angezeigt
				$html .= '<a href="'.esc_url( get_term_link( $term ) . $itemcat_url ).'">' . $term->name . '</a>';
				if ($key != array_key_last($itemTerms)) {
					$html .= ', '; //adds seperator when not last item
				}
			}
			else {
				continue;
			}
		}
	}
	else {
		foreach ($itemTerms as $term){
			$html .= '<a href="'.esc_url( get_term_link( $term ) ).'">' . $term->name . '</a>';
			if ($key != array_key_last($itemTerms)) {
				$html .= ', '; //adds seperator when not last item
			}
		}
	}
	return $html;
}

add_shortcode( 'cb_locationcats', 'shortcode_locationCats' );



 ?>
