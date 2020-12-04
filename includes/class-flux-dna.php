<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       fluxfullcircle.com
 * @since      1.0.0
 *
 * @package    Flux_Dna
 * @subpackage Flux_Dna/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Flux_Dna
 * @subpackage Flux_Dna/includes
 * @author     Flux <taahir@fluxfullcircle.com>
 */
class Flux_Dna {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Flux_Dna_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.7
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'FLUX_DNA_VERSION' ) ) {
			$this->version = FLUX_DNA_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'flux-dna';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Flux_Dna_Loader. Orchestrates the hooks of the plugin.
	 * - Flux_Dna_i18n. Defines internationalization functionality.
	 * - Flux_Dna_Admin. Defines all hooks for the admin area.
	 * - Flux_Dna_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flux-dna-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flux-dna-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-flux-dna-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-flux-dna-public.php';

		$this->loader = new Flux_Dna_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Flux_Dna_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Flux_Dna_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Flux_Dna_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		/**
		 * Add Options Page
		 */
		if( function_exists('acf_add_options_page') ) {
	
			acf_add_options_page(array(
				'page_title' 	=> 'Flux DNA Settings',
				'menu_title'	=> 'Flux DNA',
				'menu_slug' 	=> 'theme-general-settings',
				'capability'	=> 'edit_posts',
				'redirect'		=> false
			));

			acf_add_options_sub_page(array(
				'page_title' 	=> 'Analytics Settings',
				'menu_title'	=> 'Analytics',
				'parent_slug'	=> 'theme-general-settings',
			));

			acf_add_options_sub_page(array(
				'page_title' 	=> 'Brand Settings',
				'menu_title'	=> 'Brand',
				'parent_slug'	=> 'theme-general-settings',
			));
			
		}
		/**
		 * Add Custom Post Types for Accomodation, Experiences & Offers
		 */
		add_action( 'init', 'create_post_type' );
		function create_post_type() {
			register_post_type( 'post_accomodation',
				array(
					'labels' => array(
						'name' 				  => __( 'Accomodation' ),
						'singular_name' 	  => __( 'Accomodation' ),
						'menu_name'           => __( 'Accomodation' ),
						'parent_item_colon'   => __( 'Parent Accomodation' ),
						'all_items'           => __( 'All Accomodation' ),
						'view_item'           => __( 'View Accomodation'),
						'add_new_item'        => __( 'Add New Accomodation'),
						'add_new'             => __( 'Add New'),
						'edit_item'           => __( 'Edit Accomodation'),
						'update_item'         => __( 'Update Accomodation'),
						'search_items'        => __( 'Search Accomodation'),
						'not_found'           => __( 'Not Found'),
						'not_found_in_trash'  => __( 'Not found in Trash')
					),
				'menu_position' 	=> 4,
				'public' 			=> true,
				'publicly_queryable' => true,
				'has_archive' 		=> false,
				'can_export' 		=> true,
				'capability_type' 	=> 'page',
				'rewrite'           => array( "slug" => "stay" ),
				'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes'),
            	'hierarchical'      => true
				)
			);
			register_post_type( 'post_experience',
				array(
					'labels' => array(
						'name' 				  => __( 'Experiences' ),
						'singular_name' 	  => __( 'Experience' ),
						'menu_name'           => __( 'Experiences' ),
						'parent_item_colon'   => __( 'Parent Experience' ),
						'all_items'           => __( 'All Experiences' ),
						'view_item'           => __( 'View Experience'),
						'add_new_item'        => __( 'Add New Experience'),
						'add_new'             => __( 'Add New'),
						'edit_item'           => __( 'Edit Experience'),
						'update_item'         => __( 'Update Experience'),
						'search_items'        => __( 'Search Experience'),
						'not_found'           => __( 'Not Found'),
						'not_found_in_trash'  => __( 'Not found in Trash')
					),
				'menu_position' 	=> 5,
				'public' 			=> true,
				'has_archive' 		=> true,
				'can_export' 		=> true,
				'capability_type' 	=> 'page',
				'rewrite'           => array( "slug" => "experiences" ),
				'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes'),
            	'hierarchical'      => true
				)
			);
			register_post_type( 'post_offers',
				array(
					'labels' => array(
						'name' 				  => __( 'Offers' ),
						'singular_name' 	  => __( 'Offer' ),
						'menu_name'           => __( 'Offers' ),
						'parent_item_colon'   => __( 'Parent Offer' ),
						'all_items'           => __( 'All Offers' ),
						'view_item'           => __( 'View Offer'),
						'add_new_item'        => __( 'Add New Offer'),
						'add_new'             => __( 'Add New'),
						'edit_item'           => __( 'Edit Offer'),
						'update_item'         => __( 'Update Offer'),
						'search_items'        => __( 'Search Offer'),
						'not_found'           => __( 'Not Found'),
						'not_found_in_trash'  => __( 'Not found in Trash')
					),
				'menu_position' 	=> 6,
				'public' 			=> true,
				'has_archive' 		=> true,
				'can_export' 		=> true,
				'capability_type' 	=> 'page',
				'rewrite'           => array( "slug" => "offers" ),
				'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes'),
            	'hierarchical'      => true
				)
			);
		}
		/**
		 * Custom Taxonomy for Offers/Campaigns
		 */
		add_action( 'init', 'create_my_taxonomies', 0 );
		function create_my_taxonomies() {
			register_taxonomy(
				'category_offers',
				'post_offers',
				array(
					'labels' => array(
						'name' => 'Category',
						'add_new_item' => 'Add New Category',
						'new_item_name' => "New Category"
					),
					'show_ui' => true,
					'show_tagcloud' => false,
					'hierarchical' => true
				)
			);
			register_taxonomy(
				'category_experience',
				'post_experience',
				array(
					'labels' => array(
						'name' => 'Category',
						'add_new_item' => 'Add New Category',
						'new_item_name' => "New Category"
					),
					'show_ui' => true,
					'show_tagcloud' => false,
					'hierarchical' => true
				)
			);
			register_taxonomy(
				'category_accomodation',
				'post_accomodation',
				array(
					'labels' => array(
						'name' => 'Category',
						'add_new_item' => 'Add New Category',
						'new_item_name' => "New Category"
					),
					'show_ui' => true,
					'show_tagcloud' => false,
					'hierarchical' => true
				)
			);
		}
		/**
		 * Custom post query for Elementor post block
		 */
		add_action( 'elementor_pro/query/get_rooms', function ( $query ) { 
				$postid = get_the_ID(); $ids = get_post_meta($postid, 'rooms', true); if ( $ids ) { 
					$query->set( 'post__in', $ids ); 
				} 
			} 
		);

		add_action('wp_head',function(){
			$fbpixel = get_field('facebook_pixel', 'option');
			$gtag = get_field('ga_tracking_id', 'option');
			$gtm = get_field('gtm_tracking_id', 'option');
			$hotjar = get_field('hotjar_tracking_id', 'option');
			
			?>
				<?php if( get_field('ga_tracking_id', 'option') ): ?>
					<!-- Global site tag (gtag.js) - Google Analytics -->
					<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gtag ?>"></script>
					<script>
					window.dataLayer = window.dataLayer || [];
					function gtag(){dataLayer.push(arguments);}
					gtag('js', new Date());

					gtag('config', '<?php echo $gtag ?>');
					</script>
				<?php endif; ?>

				<?php if( get_field('facebook_pixel', 'option') ): ?>
					<!-- Facebook Pixel Code -->
					<script>
					!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
					n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
					n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
					t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
					document,'script','//connect.facebook.net/en_US/fbevents.js');

					fbq('init', '<?php echo $fbpixel ?>');
					fbq('track', 'PageView');</script>
					<noscript><img height='1' width='1' style='display:none'
					src='https://www.facebook.com/tr?id=<?php echo $fbpixel ?>/&ev=PageView&noscript=1'
					/></noscript>
					<!-- End Facebook Pixel Code -->
				<?php endif; ?>
				<?php if( get_field('hotjar_tracking_id', 'option') ): ?>
					<!-- Hotjar Tracking Code-->
					<script>
						(function(h,o,t,j,a,r){
							h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
							h._hjSettings={hjid:<?php echo $hotjar ?>,hjsv:6};
							a=o.getElementsByTagName('head')[0];
							r=o.createElement('script');r.async=1;
							r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
							a.appendChild(r);
						})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
					</script>
				<?php endif; ?>
				<?php if( get_field('gtm_tracking_id', 'option') ): ?>
					<!-- Google Tag Manager -->
					<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
					new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
					j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
					'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
					})(window,document,'script','dataLayer','<?php echo $gtm ?>');</script>
					<!-- End Google Tag Manager -->
				<?php endif; ?>
			<?php
		});

		add_action('wp_body_open',function(){
			$gtm = get_field('gtm_tracking_id', 'option');

			?>
			<?php if( get_field('gtm_tracking_id', 'option') ): ?>
			<!-- Google Tag Manager (noscript) -->
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtm ?>"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->
			<?php endif; ?>
			<?php
		});

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Flux_Dna_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Flux_Dna_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
