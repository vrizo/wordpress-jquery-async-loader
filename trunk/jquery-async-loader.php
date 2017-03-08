<?php
/**
 * Plugin Name: jQuery & Plugins Asynchronous Loader
 * Plugin URI: http://squirrel-research.ru/
 * Description: Loading of JavaScript resources should be done asynchronously, in a non-blocking manner, so the load time of your webpage will not be affected. But using of deferring or async loading of jQuery causes lots of problems with jQuery plugins. This plugin replaces default Wordpress's JS loader with a special jQuery loader (jQl), that's why there are no any errors with jQuery plugins.
 * Author: Vitalii Rizo
 * Author URI: http://squirrel-research.ru
 * Version: 1.1
 * Text Domain: jquery-async-loader
 *
 * Copyright: (c) 2017 Vitalii Rizo (kb@kernel-it.ru)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

// Security check:
defined( 'ABSPATH' ) or exit;

// Wordpress version check
global $wp_version;
if ( $wp_version < 4.1 ) {
	add_action( 'admin_notices', jquery_async_loader_options::render_outdated_wp_version_notice() );
	return;
}

// Init plugin after all other plugins loaded:
function init_jquery_async_loader_options() {
	jquery_async_loader_options();
}
add_action( 'plugins_loaded', 'init_jquery_async_loader_options' );


class jquery_async_loader_options {
	// Current plugin version:
	const VERSION = '1.1.0';
	
	// @var jquery_async_loader_options single instance of this plugin
	protected static $instance;
	
	public function __construct() {
		
		// Add settings and links in admin panel:
		if ( is_admin() ) {
			// load translations
			add_action( 'init', array(
				$this,
				'load_translation' 
			) );

			// add settings
			add_filter( 'admin_init', array(
				$this,
				'add_settings' 
			) );
			add_filter( 'admin_menu', array(
				$this,
				'add_settings_page' 
			) );
			
			// add plugin links
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array(
				$this,
				'add_plugin_links' 
			) );

			// add jQuery loader
			add_action( 'admin_enqueue_scripts', array (
				$this,
				'jQuery_loader_script'
			), 5 );

		} else {
			// Do things in front end
			add_action( 'init', array (
				$this,
				're_register_jquery'
			) );

			// add jQuery loader
			add_action( 'wp_head', array (
				$this,
				'jQuery_loader_script'
			), 5 );

		}

		// Modify <script> tags
		add_filter( 'script_loader_tag', array (
			$this,
			'modify_script_tags'
		), 10, 2 );

	}
	
	// Ensures only one instance is/can be loaded
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Singleton pattern: prevent creating more instances by clone and unserialize:
	 */
	public function __clone() {
		/* translators: Placeholders: %s - plugin name */
		_doing_it_wrong( __FUNCTION__, sprintf( esc_html__( 'You cannot clone instances of %s.', 'jquery-async-loader' ), 'jQuery Async Loader' ), '2.4.0' );
	}
	
	public function __wakeup() {
		/* translators: Placeholders: %s - plugin name */
		_doing_it_wrong( __FUNCTION__, sprintf( esc_html__( 'You cannot unserialize instances of %s.', 'jquery-async-loader' ), 'jQuery Async Loader' ), '2.4.0' );
	}
	
	// Add plugin links: Settings and Author web site:
	public function add_plugin_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'options-general.php?page=jquery_async_loader' ) . '">' . __( 'Settings', 'jquery-async-loader' ) . '</a>',
			'<a href="http://squirrel-research.ru/" target="_blank">' . __( 'Author', 'jquery-async-loader' ) . '</a>' 
		);
		return array_merge( $plugin_links, $links );
	}
	
	/**********************/
	/** Frontend methods **/
	/**********************/
	
	/**
	 * Replace jQuery with minified CDN version
	 */
	public function re_register_jquery() {
		$defaults = array(
			'replace_jquery' => 1,
		);
		$options = wp_parse_args(get_option('jquery_async_loader_options'), $defaults);

		if ( $options['replace_jquery'] === 1 ) {
			wp_deregister_script('jquery');
			wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', FALSE, '1.11.0', TRUE);
			wp_enqueue_script('jquery');
		}
	}

	/**
	 * Add jQl - jQuery Async Loader script
	 * Author URL: https://github.com/Cerdic/jQl
	 */
	public function jQuery_loader_script() {
    	echo '<script type="text/javascript">var jQl={q:[],dq:[],gs:[],ready:function(a){"function"==typeof a&&jQl.q.push(a);return jQl},getScript:function(a,c){jQl.gs.push([a,c])},unq:function(){for(var a=0;a<jQl.q.length;a++)jQl.q[a]();jQl.q=[]},ungs:function(){for(var a=0;a<jQl.gs.length;a++)jQuery.getScript(jQl.gs[a][0],jQl.gs[a][1]);jQl.gs=[]},bId:null,boot:function(a){"undefined"==typeof window.jQuery.fn?jQl.bId||(jQl.bId=setInterval(function(){jQl.boot(a)},25)):(jQl.bId&&clearInterval(jQl.bId),jQl.bId=0,jQl.unqjQdep(),jQl.ungs(),jQuery(jQl.unq()), "function"==typeof a&&a())},booted:function(){return 0===jQl.bId},loadjQ:function(a,c){setTimeout(function(){var b=document.createElement("script");b.src=a;document.getElementsByTagName("head")[0].appendChild(b)},1);jQl.boot(c)},loadjQdep:function(a){jQl.loadxhr(a,jQl.qdep)},qdep:function(a){a&&("undefined"!==typeof window.jQuery.fn&&!jQl.dq.length?jQl.rs(a):jQl.dq.push(a))},unqjQdep:function(){if("undefined"==typeof window.jQuery.fn)setTimeout(jQl.unqjQdep,50);else{for(var a=0;a<jQl.dq.length;a++)jQl.rs(jQl.dq[a]); jQl.dq=[]}},rs:function(a){var c=document.createElement("script");document.getElementsByTagName("head")[0].appendChild(c);c.text=a},loadxhr:function(a,c){var b;b=jQl.getxo();b.onreadystatechange=function(){4!=b.readyState||200!=b.status||c(b.responseText,a)};try{b.open("GET",a,!0),b.send("")}catch(d){}},getxo:function(){var a=!1;try{a=new XMLHttpRequest}catch(c){for(var b=["MSXML2.XMLHTTP.5.0","MSXML2.XMLHTTP.4.0","MSXML2.XMLHTTP.3.0","MSXML2.XMLHTTP","Microsoft.XMLHTTP"],d=0;d<b.length;++d){try{a= new ActiveXObject(b[d])}catch(e){continue}break}}finally{return a}}};if("undefined"==typeof window.jQuery){var $=jQl.ready,jQuery=$;$.getScript=jQl.getScript};</script><script type="text/javascript">jQl.boot();</script>';
	}

	public function modify_script_tags( $tag ) {

		$defaults = array(
			'replace_jquery' => 1,
			'enable_on_backend' => 0
		);
		$options = wp_parse_args(get_option('jquery_async_loader_options'), $defaults);

		// Additional check to be sure that this code is not executed on admin pages:
		if( ( is_admin() ) && ( $options['enable_on_backend'] !== 1 ) ) {
			return $tag;
		}
		
		// Get src urls of scripts:
		preg_match("/<script(.*)src(.*)=(.*)'(.*)'/U", $tag, $match);
		
		// If this is jquery than use loadjQ function
		if (strpos($match[4], 'jquery.min.js') !== false) {
			return "<script type='text/javascript'>jQl.loadjQ('".$match[4]."');</script>";
		}
		
		// In any other cases use loadjQdep
		return "<script type='text/javascript'>jQl.loadjQdep('".$match[4]."');</script>";
	}

	/**
	 * Load Translations
	 */
	public function load_translation() {
		load_plugin_textdomain( 'jquery-async-loader', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages' );
	}
		
	
	/**
	 * Renders a notice when Wordpress version is outdated
	 */
	public static function render_outdated_wp_version_notice() {
		$message = sprintf( /* translators: %1$s and %2$s are <strong> tags. %3$s and %4$s are <a> tags. */ esc_html__( '%1$jQuery Async Loader plugin is inactive.%2$s This plugin requires Wordpress 4.1 or newer. Please %3$supdate Wordpress to version 4.1 or newer%4$s', 'jquery-async-loader' ), '<strong>', '</strong>', '<a href="' . admin_url() . '">', '&nbsp;&raquo;</a>' );
		
		printf( '<div class="error"><p>%s</p></div>', $message );
	}
		
	/********************/
	/** Plugin methods **/
	/********************/
	
	/**
	 * Add Settings
	 */
	public function add_settings() {
		register_setting( 'jquery_async_loader_options', 'jquery_async_loader_options', array(
				$this,
				'jquery_async_loader_options_validate'
		) );
	}
	public function add_settings_page() {
		add_options_page( __( 'jQuery Async Loader Settings', 'jquery_async_loader' ), __( 'jQuery Async Loader', 'jquery_async_loader' ), 'manage_options', 'jquery_async_loader', array(
				$this,
				'jquery_async_loader_options_page'
		) );
	}
	
	public function jquery_async_loader_options_page() {
		// Display the admin options page
		if ( ! isset( $_REQUEST['settings-updated'] ) )
			$_REQUEST['settings-updated'] = false;
		?>
		<div class="wrap">
			<h1><?php _e( 'jQuery & Plugins Async Loader Settings', 'jquery-async-loader' ) ?></h1>
			
			<form action="options.php" method="post">
				<?php
				settings_fields('jquery_async_loader_options');
				$defaults = array(
					'replace_jquery' => 1,
					'enable_on_backend' => 0
				);
				$options = wp_parse_args(get_option('jquery_async_loader_options'), $defaults);
				?>
			
				<h2 class="title"><?php _e('Description and recommendations', 'jquery-async-loader'); ?></h2>

				<p><?php _e( 'Loading of JavaScript resources should be done asynchronously, in a non-blocking manner, so the load time of your webpage will not be affected. But using of deferring or async loading of jQuery causes lots of problems with jQuery plugins. This plugin replaces default Wordpress\'s JS loader with a special jQuery loader (jQl), that\'s why there are no any errors with jQuery plugins.', 'jquery-async-loader' ); ?></p>

				<p><?php echo sprintf( /* translators: %1$s and %2$s are <strong> tags. %3$s and %4$s are links to the plugins. %5$s and %$6s code tags with quotes. */ esc_html__( '%1$sIt\'s highly recommended to use any concatenate plugin,%2$s e.g., %3$s or %4$s.  Please, do not add jQuery to the ignore list, because this plugin fixes %5$sundefined jQuery%6$s errors on the console log. Also, it is allowed to use defer or async parsing of JS files.', 'jquery-async-loader' ), '<strong>', '</strong>', '<a href="https://wordpress.org/plugins/fast-velocity-minify/" target="_blank">Fast Velocity Minify</a>', '<a href="https://wordpress.org/plugins/wp-fastest-cache/" target="_blank">WP Fastest Cache</a>', '<code>&#147;', '&#148;</code>' ); ?></p>

				<h2 class="title"><?php _e('Settings', 'jquery_async_loader'); ?></h2>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e( 'Replace jQuery library with&nbsp;minified CDN version (recommended)', 'jquery-async-loader' ); ?></th>
						<td><input id="jquery_async_loader_options[replace_jquery]" name="jquery_async_loader_options[replace_jquery]" type="checkbox" value="1" <?php checked( '1', $options['replace_jquery'] ); ?> />
						<label class="description" for="jquery_async_loader_options[replace_jquery]"><?php _e( 'Replace', 'jquery-async-loader' ); ?></label></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Use it on the&nbsp;Back-end (admin&nbsp;pages)', 'jquery-async-loader' ); ?></th>
						<td><input id="jquery_async_loader_options[enable_on_backend]" name="jquery_async_loader_options[enable_on_backend]" type="checkbox" value="1" <?php checked( '1', $options['enable_on_backend'] ); ?> />
						<label class="description" for="jquery_async_loader_options[enable_on_backend]"><?php _e( 'Enable', 'jquery-async-loader' ); ?></label><p class="description"><?php _e('It might be useful sometimes, but it\'s recommended to disable this option.', 'jquery-async-loader' ); ?></p></td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'jquery-async-loader' ); ?>" />
				</p>

				<p><?php echo sprintf( /* translators: %1$s and %2$s are <code> tags. */ esc_html__( 'For advanced users: If you\'re seeing any scripts loaded using %1$s<script>%2$s tag in your page source code (excluding Google Analytics, FB pixel, etc), please make sure that all the scripts are included correctly through %1$swp_enqueue_script%2$s or similar functions.', 'jquery-async-loader' ), '<code>', '</code>' ); ?></p>

				<p><?php echo sprintf( /* translators: %1$s is a link to Cerdic Morin github page. */ esc_html__( 'Special thanks to %1$s, who is the author of jQl.', 'jquery-async-loader' ), '<a href="https://github.com/Cerdic/jQl" target="_blank">Cédric Morin</a>' ); ?></p>
			</form>

		</div>
		<?php
	}

	public function jquery_async_loader_options_validate( $input ) {
		// Our checkbox value is either 0 or 1
		if ( ! isset( $input['replace_jquery'] ) )
			$input['replace_jquery'] = null;
		$input['replace_jquery'] = ( $input['replace_jquery'] == 1 ? 1 : 0 );

		if ( ! isset( $input['enable_on_backend'] ) )
			$input['enable_on_backend'] = null;
		$input['enable_on_backend'] = ( $input['enable_on_backend'] == 1 ? 1 : 0 );

		return $input;
	}
	
	
} // class jquery_async_loader_options end


/**
 * Returns the One True Instance of jQuery Async Loader
 */
function jquery_async_loader_options() {
	return jquery_async_loader_options::instance();
}