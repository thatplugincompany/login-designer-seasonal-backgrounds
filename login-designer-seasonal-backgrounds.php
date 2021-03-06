<?php
/**
 * Plugin Name: Login Designer — Seasonal Backgrounds
 * Plugin URI: @@pkg.plugin_uri
 * Description: @@pkg.description
 * Author: @@pkg.author
 * Author URI: @@pkg.author_uri
 * Version: @@pkg.version
 * Text Domain: @@textdomain
 * Domain Path: languages
 * Requires at least: @@pkg.requires
 * Tested up to: @@pkg.tested_up_to
 *
 * @@pkg.title is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * @@pkg.title is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with @@pkg.title. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   @@pkg.title
 * @author    @@pkg.author
 * @license   @@pkg.license
 * @version   @@pkg.version
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Login_Designer_Seasonal_Backgrounds' ) ) :

	/**
	 * Main Login Designer Seasonal Backgrounds Class.
	 *
	 * @since 1.0.0
	 */
	final class Login_Designer_Seasonal_Backgrounds {
		/** Singleton *************************************************************/

		/**
		 * Login_Designer_Seasonal_Backgrounds The one true Login_Designer_Seasonal_Backgrounds
		 *
		 * @var string $instance
		 */
		private static $instance;

		/**
		 * Plugin Version
		 *
		 * @var string $version
		 */
		private static $version = '@@pkg.version';

		/**
		 * Plugin Download ID
		 *
		 * @var string $id
		 */
		private static $id = '@@pkg.downloadid';

		/**
		 * Main Login_Designer_Seasonal_Backgrounds Instance.
		 *
		 * Insures that only one instance of Login_Designer_Seasonal_Backgrounds exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * @static
		 * @staticvar array $instance
		 * @uses Login_Designer_Seasonal_Backgrounds::setup_constants() Setup the constants needed.
		 * @uses Login_Designer_Seasonal_Backgrounds::includes() Include the required files.
		 * @uses Login_Designer_Seasonal_Backgrounds::load_textdomain() load the language files.
		 * @see LOGIN_DESIGNER_SEASONAL_BACKGROUNDS()
		 * @return object|Login_Designer_Seasonal_Backgrounds The one true Login_Designer_Seasonal_Backgrounds
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Login_Designer_Seasonal_Backgrounds ) ) {
				self::$instance = new Login_Designer_Seasonal_Backgrounds();
				self::$instance->constants();
				self::$instance->actions();
				self::$instance->load_textdomain();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', '@@textdomain' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', '@@textdomain' ), '1.0' );
		}

		/**
		 * Setup plugin constants.
		 *
		 * @access private
		 * @return void
		 */
		private function constants() {
			$this->define( 'LOGIN_DESIGNER_SEASONAL_BACKGROUNDS_VERSION', '@@pkg.version' );
			$this->define( 'LOGIN_DESIGNER_SEASONAL_BACKGROUNDS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			$this->define( 'LOGIN_DESIGNER_SEASONAL_BACKGROUNDS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'LOGIN_DESIGNER_SEASONAL_BACKGROUNDS_PLUGIN_FILE', __FILE__ );
			$this->define( 'LOGIN_DESIGNER_SEASONAL_BACKGROUNDS_ABSPATH', dirname( __FILE__ ) . '/' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string|string $name Name of the definition.
		 * @param  string|bool   $value Default value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Load the actions & filters.
		 *
		 * @return void
		 */
		public function actions() {
			// Actions.
			add_action( 'admin_init', array( $this, 'updater' ) );
			add_action( 'login_enqueue_scripts', array( $this, 'customizer_css' ) );

			// Filters.
			add_filter( 'login_designer_backgrounds', array( $this, 'seasonal_backgrounds' ) );
			add_filter( 'login_designer_extension_background_options', array( $this, 'extended_backgrounds_array' ) );
			add_filter( 'login_designer_control_localization', array( $this, 'control_localization' ) );
			add_filter( 'login_designer_customize_preview_localization', array( $this, 'preview_localization' ) );
		}

		/**
		 * Adds the seasonal background images to the custom gallery Customizer control.
		 *
		 * @param  array $backgrounds Default backgrounds from the Login Designer plugin.
		 * @return array of default fonts, plus the new typekit additions.
		 */
		public function seasonal_backgrounds( $backgrounds ) {

			$image_dir = LOGIN_DESIGNER_SEASONAL_BACKGROUNDS_PLUGIN_URL . 'assets/images/';

			// Change the "winter-01" key and leave the background images in the plugin folder (at least for month or so).
			$seasonal_backgrounds = array(
				'seasonal-winter-01' => esc_url( $image_dir ) . 'seasonal-winter-01-sml.jpg',
				'seasonal-winter-02' => esc_url( $image_dir ) . 'seasonal-winter-02-sml.jpg',
				'seasonal-winter-03' => esc_url( $image_dir ) . 'seasonal-winter-03-sml.jpg',
				'seasonal-winter-04' => esc_url( $image_dir ) . 'seasonal-winter-04-sml.jpg',
				'seasonal-winter-05' => esc_url( $image_dir ) . 'seasonal-winter-05-sml.jpg',
			);

			// Combine the two arrays.
			$backgrounds = array_merge( $backgrounds, $seasonal_backgrounds );

			return $backgrounds;
		}

		/**
		 * Option titles.
		 *
		 * @return array of default fonts, plus the new typekit additions.
		 */
		public function options() {

			// Change the colors whenever needed.
			$options = array(
				'seasonal_option_01' => 'seasonal-winter-01',
				'seasonal_option_02' => 'seasonal-winter-02',
				'seasonal_option_03' => 'seasonal-winter-03',
				'seasonal_option_04' => 'seasonal-winter-04',
				'seasonal_option_05' => 'seasonal-winter-05',
			);

			return $options;
		}

		/**
		 * Filters currrent backgrounds options and adds new backgrounds.
		 *
		 * @param  array $backgrounds Current backgrounds.
		 * @return array of default fonts, plus the new typekit additions.
		 */
		public function extended_backgrounds_array( $backgrounds ) {

			// Get the option values.
			$options = $this->options();

			// Combine the two arrays.
			$backgrounds = array_merge( $backgrounds, $options );

			return $backgrounds;
		}

		/**
		 * Adds corresponding seasonal option titles and background colors for the controls javascript file.
		 *
		 * @param  array $localize Default control localization.
		 * @return array of default fonts, plus the new typekit additions.
		 */
		public function control_localization( $localize ) {

			// Get the option values.
			$options = $this->options( '' );

			// Change the colors whenever needed.
			$colors = array(
				'seasonal_bg_color_01' => '#dfe0e2',
				'seasonal_bg_color_02' => '#131522',
				'seasonal_bg_color_03' => '#cad1de',
				'seasonal_bg_color_04' => '#1f2214',
				'seasonal_bg_color_05' => '#dadad8',
			);

			// Combine the three arrays.
			$localize = array_merge( $localize, $options, $colors );

			return $localize;
		}

		/**
		 * Enqueue the stylesheets required.
		 *
		 * @access public
		 */
		public function customizer_css() {

			// Get the options.
			$options = get_option( 'login_designer' );

			// Start CSS Variable.
			$css = '';

			if ( ! empty( $options ) ) :

				// Background image gallery. Only display if there's no custom background image.
				if ( isset( $options['bg_image_gallery'] ) && 'none' !== $options['bg_image_gallery'] && empty( $options['bg_image'] ) ) {

					$extension_backgrounds = null;

					// Check first if one of this extension's background is selected.
					if ( in_array( $options['bg_image_gallery'], $this->options(), true ) ) {

						$image_dir = LOGIN_DESIGNER_SEASONAL_BACKGROUNDS_PLUGIN_URL . 'assets/images/';

						// Get the image's url.
						$url = $image_dir . $options['bg_image_gallery'] . '.jpg';

						$css .= 'body.login, #login-designer-background { background-image: url(" ' . esc_url( $url ) . ' "); }';
					}
				}

				// Combine the values from above and minifiy them.
				$css = preg_replace( '#/\*.*?\*/#s', '', $css );
				$css = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css );
				$css = preg_replace( '/\s\s+(.*)/', '$1', $css );

				// Add inline style.
				wp_add_inline_style( 'login', wp_strip_all_tags( $css ) );

			endif;
		}

		/**
		 * Adds corresponding seasonal background colors for preview javascript file.
		 *
		 * @param  array $localize Default control localization.
		 * @return array of default fonts, plus the new typekit additions.
		 */
		public function preview_localization( $localize ) {

			// Get the option values.
			$options = $this->options();

			// Change the colors whenever needed.
			$url = array(
				'seasonal_plugin_url' => LOGIN_DESIGNER_SEASONAL_BACKGROUNDS_PLUGIN_URL . 'assets/images/',
			);

			// Combine the three arrays.
			$localize = array_merge( $localize, $options, $url );

			return $localize;
		}

		/**
		 * Custom plugin updater.
		 *
		 * @access private
		 * @return void
		 */
		public function updater() {

			if ( ! is_admin() ) {
				return;
			}

			// Check for required classes.
			if ( ! class_exists( 'Login_Designer_License_Handler' ) || ! class_exists( 'Login_Designer_Extension_Updater' ) ) {
				return;
			}

			// Retrieve license information.
			$handler  = new Login_Designer_License_Handler();
			$key      = trim( $handler->key() );
			$shop_url = esc_url( $handler->shop_url() );
			$author   = esc_attr( $handler->author() );

			$updater = new Login_Designer_Extension_Updater(
				$shop_url, __FILE__, array(
					'version' => self::$version,
					'license' => $key,
					'author'  => $author,
					'item_id' => self::$id,
					'beta'    => false,
				)
			);
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( '@@textdomain', false, dirname( plugin_basename( LOGIN_DESIGNER_SEASONAL_BACKGROUNDS_PLUGIN_DIR ) ) . '/languages/' );
		}
	}

endif; // End if class_exists check.

/**
 * The main function for that returns Login_Designer_Seasonal_Backgrounds
 *
 * The main function responsible for returning the one true Login_Designer_Seasonal_Backgrounds
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $login_designer_seasonal_backgrounds = login_designer_seasonal_backgrounds(); ?>
 *
 * @since 1.0.0
 * @return object|Login_Designer_Seasonal_Backgrounds The one true Login_Designer_Seasonal_Backgrounds Instance.
 */
function login_designer_seasonal_backgrounds() {

	// Check for Login Designer.
	if ( ! class_exists( 'Login_Designer' ) ) {

		// Check for the activation class.
		if ( ! class_exists( 'Login_Designer_Extension_Activation' ) ) {
			require_once 'includes/class-login-designer-extension-activation.php';
		}

		$activation = new Login_Designer_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();

	} else {
		return Login_Designer_Seasonal_Backgrounds::instance();
	}
}
add_action( 'plugins_loaded', 'login_designer_seasonal_backgrounds' );
