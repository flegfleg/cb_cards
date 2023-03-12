<?php

function enqueue_basicgrid_styles(){
	wp_register_style('basicgrid-css', CB_CATALOGUE_PLUGIN_URI . '/inc/View/css/basicgrid.css', __FILE__);
	wp_enqueue_style('basicgrid-css');	
}

function create_basic_grid_from_posts($items,$itemAvailabilities,$hideCardMeta=True,$css_class='') {
  require_once(CB_CATALOGUE_PLUGIN_PATH . '/inc/View/itemAvailability.php'); //Um Kartenmeta zu rendern
	$cardMeta_class = 'card__meta card__meta--last';
	if ($hideCardMeta){
		$cardMeta_class = 'card__meta card__meta--last card__hidden';
	}
	enqueue_basicgrid_styles();
	if ( $items ) {
			$print = '<div class="grid__wrapper">';
			foreach ($items as $item) {
				$itemID = $item->ID;
				$itemAvailability = $itemAvailabilities[$itemID];
				$item_title = $item->post_title;
				$item_permalink = get_permalink($itemID);
				$itemThumbnailURL = cb_catalogue_get_the_post_thumbnail_url($itemID);
				$itemLocAddress = cb_item_locAdress($itemID);
			
				$print .= '<div class="grid '.$css_class.'">';
					$print .= '<div class="card" id="'.$itemID.'" style="background-image: url('.esc_url($itemThumbnailURL).');">';
							$print .= '<div class="card__overlay card__overlay--white">';
								$print .= '<div class="card__overlay-content">';
									$print .= '<h3><a href="'.$item_permalink.'" class="card__title">'.$item_title.'</a></h3>';
									$print .= '<ul class="'.$cardMeta_class.'">';
										$print .= '<li class="address"><a href="'.$item_permalink.'"><i class="fas fa-map-marker"></i>'.$itemLocAddress.'</a></li>';
										$print .= '<li class="availability">' . render_item_availability($itemAvailability) . '</li>';
									$print .= '</ul>';
								$print .= '</div><!-- end:card__overlay-content -->';
						$print .= '</div><!-- end:card__overlay -->';
					$print .= '</div><!-- end:card -->';
				$print .= '</div><!-- end:grid -->';
			}
			$print .= '</div><!-- end:grid__wrapper -->';
			return $print;
		}
		else {
			return False;
		}

}
/*-------------------------------------------------------------------------------
 * ENDE Create PostGrid from Posts
 * -------------------------------------------------------------------------------
*/

 ?>