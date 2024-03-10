# CB Catalogue

Catalogue-like Navigation for CommonsBooking items. 
Started from on https://github.com/hansmorb/freielasten_wptheme 

**NOTE: The just-released CommonsBooking version (2.9) brings a new frontend that has a better implementation of a gallery view. This repo will not be updated.**  

## Quick Start

Insert the shortcodes [cb_catalogue_filter] and [cb_catalogue_items] into a page.


### [cb_catalogue_items] 

Grid of all items, args: 

````
   $atts = shortcode_atts( array(
   'itemcat' 		=> '',	// set cat slug to show only one cat
   'class' 		=> '',
   'hidedefault' 	=> TRUE,
    'sortbyavailability' => TRUE,
   'layout' => 'basic' // or "masonry"
),$atts);
````
Masonry Layout
![layout-masonry](https://user-images.githubusercontent.com/4009931/224728331-8b43e201-d77a-49a7-99cd-93027803b762.jpg)
Basic Layout
![layout-basic](https://user-images.githubusercontent.com/4009931/224728337-df15f7a2-01a3-4503-a35d-2bd6030d3b01.jpg)



### [cb_catalogue_filter] 

Filter bar, layout "filter" or "grid"
![filterbar](https://user-images.githubusercontent.com/4009931/224728245-b82a46d6-f1f1-4d5a-a790-e1102567cdc3.jpg)

Filter bar with the categories, args:

````
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
````
