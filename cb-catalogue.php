<?php
/**
 * CB Catalogue
 *
 * @package       CB Catalogue
 * @author        Florian Egermann
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   CB Catalogue
 * Plugin URI:    fleg.de
 * Description:   Filter catalogue for CommonsBooking (v2+)
 * Version:       1.0.0
 * Author:        Florian Egermann
 * Author URI:    https://www.fleg.de
 * Text Domain:   cb-catalogue
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with CB Catalogue. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CB_CATALOGUE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CB_CATALOGUE_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

require_once ( CB_CATALOGUE_PLUGIN_PATH . 'inc/QueryFunctions.php' );
require_once ( CB_CATALOGUE_PLUGIN_PATH . 'inc/Shortcodes.php' ); 
require_once ( CB_CATALOGUE_PLUGIN_PATH . 'inc/helpers.php' ); 

wp_register_style('filterbar-css', CB_CATALOGUE_PLUGIN_URI . '/inc/View/css/filterbar.css', __FILE__);
wp_enqueue_style('filterbar-css');	


// Add a query var for filtering items by item cat
function add_query_vars_filter( $vars ){
  $vars[] = "itemcat";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );