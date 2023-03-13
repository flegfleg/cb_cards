# CB Catalogue

Catalogue-like Navigation for CommonsBooking items. 
Started from on https://github.com/hansmorb/freielasten_wptheme 


## Quick Start

Insert the shortcodes [cb_catalogue_filter] and [cb_catalogue_items] into a page.


### [cb_catalogue_items] 

Grid of all items, args: 

''''
   $atts = shortcode_atts( array(
   'itemcat' 		=> '',	// set cat slug to show only one cat
   'class' 		=> '',
   'hidedefault' 	=> TRUE,
    'sortbyavailability' => TRUE,
   'layout' => 'basic' // or "masonry"
),$atts);
''''




### [cb_catalogue_filter] 

Filter bar with the categories, args:

''''
   $args = shortcode_atts( array(
   'orderby'					=> 'title',
   'order'						=> 'ASC',
   'include_empty' 			=> TRUE,
   'include_filter_all' 		=> FALSE,
   'include_unavailable_items' => TRUE,
   'taxonomy' 					=> 'cb_items_category',
   'css_class'					=> '',
   'catalogue_page_id' 		=> '', // redirect to this page
   'layout' 					=> 'filter' // or "grid"
),$atts);
''''