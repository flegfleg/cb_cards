<?php
/**
 * CB Cards
 *
 * @package       CBCARDS
 * @author        Florian Egermann
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   CB Cards
 * Plugin URI:    fleg.de
 * Description:   This is some demo short description...
 * Version:       1.0.0
 * Author:        Florian Egermann
 * Author URI:    https://www.fleg.de
 * Text Domain:   cb-cards
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with CB Cards. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CB_CARDS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once ( CB_CARDS_PLUGIN_PATH . 'inc/QueryFunctions.php');
require_once (CB_CARDS_PLUGIN_PATH . 'inc/Shortcodes.php'); 

// Add a query var for filtering posts
function add_query_vars_filter( $vars ){
  $vars[] = "itemcat";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );