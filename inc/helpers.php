<?php
/**
* Helpers
*/

function cb_catalogue_get_the_post_thumbnail_url( $post_ID, $size='medium' ) {
	if (has_post_thumbnail( $post_ID ) ) {
		return get_the_post_thumbnail_url( $post_ID, $size );
	} else { 
		return CB_CATALOGUE_PLUGIN_URI . '/assets/fallback.png';
	}
}