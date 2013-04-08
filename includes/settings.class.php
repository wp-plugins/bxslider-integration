<?php
/*  Copyright 2013 MarvinLabs (contact@marvinlabs.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/


if (!class_exists('BXSG_Settings')) :

/**
 * Creates the UI to change the plugin settings in the admin area. Also used to access the plugin settings 
 * stored in the DB (@see BXSG_Plugin::get_option)
 */
class BXSG_Settings {
	
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->setup();	
		$this->reload_options();
	}

	/**
	 * Get the value of a particular plugin option
	 * 
	 * @param string $option_id the ID of the option to get
	 * @return mixed the value
	 */
	public function get_option( $option_id ) {
    	return $this->options[ $option_id ];
	}
	
	/**
	 * Setup the WordPress hooks we need
	 */
	public function setup() {
		if ( is_admin() ) {
	    	add_action('admin_menu', array( &$this, 'add_settings_menu_item' ) );
	    	add_action('admin_init', array( &$this, 'page_init' ) );
		}
	}
	
	/**
	 * Add the menu item
	 */
	public function add_settings_menu_item() {
		add_options_page( 
				__( 'bxSlider integration', 'bxsg' ), 
				__( 'bxSlider integration', 'bxsg' ), 
				'manage_options', 
				self::$OPTIONS_PAGE_SLUG, 
				array( &$this, 'print_settings_page' ) );
    }

	/**
	 * Output the settings page
	 */
    public function print_settings_page(){
        include( BXSG_INCLUDES_DIR . '/settings.view.php' );
    }
	
	/**
	 * Register the settings
	 */
    public function page_init(){		
		register_setting( self::$OPTIONS_GROUP, self::$OPTIONS_GROUP, array( &$this, 'validate_options' ) );
		
		// General settings
        add_settings_section(
				'bxsg_section_general_settings',
				__('General Settings', 'bxsg'),
				array( &$this, 'print_section_info_general_settings' ),
				self::$OPTIONS_PAGE_SLUG
			);	
			
		add_settings_field(
				self::$OPTION_INCLUDE_CSS, 
				__('Include CSS', 'bxsg'),
				array( &$this, 'print_input_field' ), 
				self::$OPTIONS_PAGE_SLUG,
				'bxsg_section_general_settings',
				array(
					'option_id' => self::$OPTION_INCLUDE_CSS,
					'type' 		=> 'checkbox',
					'caption'	=> __( 'Include the default stylesheet.', 'bxsg' )
						. '<p class="description">'
						. __( 'If not, you should style the slider yourself in your theme.', 'bxsg' )
						. '</p>' )
			);	
		
		// Gallery shortcode settings
		add_settings_section(
				'bxsg_section_gallery_shortcode',
				__('Gallery Shortcodes', 'bxsg'),
				array( &$this, 'print_section_info_gallery_shortcode' ),
				self::$OPTIONS_PAGE_SLUG
			);
		
		add_settings_field(
				self::$OPTION_GS_REPLACE_DEFAULT_GALLERIES, 
				__('Default WordPress galleries', 'bxsg'),
				array( &$this, 'print_input_field' ), 
				self::$OPTIONS_PAGE_SLUG,
				'bxsg_section_gallery_shortcode',
				array( 
					'option_id' => self::$OPTION_GS_REPLACE_DEFAULT_GALLERIES, 
	    			'type' 		=> 'checkbox', 
	    			'caption'	=> __( 'Replace the default WordPress gallery for a bxSlider based one.', 'bxsg' ) 
	    				. '<p class="description">'
	    				. __( 'All the galleries included in your posts and pages with the [gallery] shortcode will turn into cool dynamic galleries using the bxSlider script.', 'bxsg' )
	    				. '</p>' )
			);	
		
		add_settings_field(
				self::$OPTION_GS_EXCLUDE_FEATURED_IMAGE, 
				__('Exclude featured image', 'bxsg'),
				array( &$this, 'print_input_field' ), 
				self::$OPTIONS_PAGE_SLUG,
				'bxsg_section_gallery_shortcode',
				array( 
					'option_id' => self::$OPTION_GS_EXCLUDE_FEATURED_IMAGE, 
	    			'type' 		=> 'checkbox', 
	    			'caption'	=> __( 'Exclude the featured image from the galleries that show all the post images.', 'bxsg' ) 
	    				. '<p class="description">'
	    				. __( 'This can be overriden in the shortcode itself by passing the parameter <code>exclude_featured=0</code> or <code>exclude_featured=1</code>.', 'bxsg' )
	    				. '</p>' )
			);	
		
		add_settings_field(
				self::$OPTION_GS_HIDE_CAROUSEL, 
				__('Hide the carousel', 'bxsg'),
				array( &$this, 'print_input_field' ), 
				self::$OPTIONS_PAGE_SLUG,
				'bxsg_section_gallery_shortcode',
				array( 
					'option_id' => self::$OPTION_GS_HIDE_CAROUSEL, 
	    			'type' 		=> 'checkbox', 
	    			'caption'	=> __( 'You can hide the default thumbnail carousel shown above the gallery.', 'bxsg' ) 
	    				. '<p class="description">'
	    				. __( 'This can be overriden in the shortcode itself by passing the parameter <code>hide_carousel=0</code> or <code>hide_carousel=1</code>.', 'bxsg' )
	    				. '</p>' )
			);	
		
		add_settings_field(
				self::$OPTION_GS_CAROUSEL_THUMB_WIDTH, 
				__('Carousel thumb width', 'bxsg'),
				array( &$this, 'print_input_field' ), 
				self::$OPTIONS_PAGE_SLUG,
				'bxsg_section_gallery_shortcode',
				array( 
					'option_id' => self::$OPTION_GS_CAROUSEL_THUMB_WIDTH, 
	    			'type' 		=> 'text', 
	    			'caption'	=> '<p class="description"><em>'
					    				. __( 'a number without unit; Will be interpreted as a value in pixels', 'bxsg' )
	    								. '</em></p>' )
			);	
		
		add_settings_field(
				self::$OPTION_GS_CAROUSEL_THUMB_MARGIN, 
				__('Carousel thumb margin', 'bxsg'),
				array( &$this, 'print_input_field' ), 
				self::$OPTIONS_PAGE_SLUG,
				'bxsg_section_gallery_shortcode',
				array( 
					'option_id' => self::$OPTION_GS_CAROUSEL_THUMB_MARGIN, 
	    			'type' 		=> 'text', 
	    			'caption'	=> '<p class="description"><em>'
	    								. __( 'a number without unit; Will be interpreted as a value in pixels', 'bxsg' )
	    								. '</em></p>' )
			);	
		
		add_settings_field(
				self::$OPTION_GS_CAROUSEL_MIN_THUMBS, 
				__('Carousel min thumbs', 'bxsg'),
				array( &$this, 'print_input_field' ), 
				self::$OPTIONS_PAGE_SLUG,
				'bxsg_section_gallery_shortcode',
				array( 
					'option_id' => self::$OPTION_GS_CAROUSEL_MIN_THUMBS, 
	    			'type' 		=> 'text', 
	    			'caption'	=> '<p class="description"><em>'
					    				. __( 'The minimum number of thumbnails to be shown. Thumbnails will be sized down if carousel becomes smaller than the original size', 'bxsg' )
	    								. '</em></p>' )
			);	
		
		add_settings_field(
				self::$OPTION_GS_CAROUSEL_MAX_THUMBS, 
				__('Carousel max thumbs', 'bxsg'),
				array( &$this, 'print_input_field' ), 
				self::$OPTIONS_PAGE_SLUG,
				'bxsg_section_gallery_shortcode',
				array( 
					'option_id' => self::$OPTION_GS_CAROUSEL_MAX_THUMBS, 
	    			'type' 		=> 'text', 
	    			'caption'	=> '<p class="description"><em>'
					    				. __( 'The maximum number of thumbnails to be shown. Thumbnails will be sized up if carousel becomes larger than the original size.', 'bxsg' )
	    								. '</em></p>' )
			);	
		
		add_settings_field(
				self::$OPTION_GS_CAROUSEL_THUMBS_MOVE, 
				__('Skip thumbnails', 'bxsg'),
				array( &$this, 'print_input_field' ), 
				self::$OPTIONS_PAGE_SLUG,
				'bxsg_section_gallery_shortcode',
				array( 
					'option_id' => self::$OPTION_GS_CAROUSEL_THUMBS_MOVE, 
	    			'type' 		=> 'text', 
	    			'caption'	=> '<p class="description"><em>'
					    				. __( 'The number of thumbnails to move on transition. This value must be >= min thumbnails, and <= max thumbnails. If zero (default), the number of fully-visible thumbnails will be used.', 'bxsg' )
	    								. '</em></p>' )
			);	

		// General slider shortcode settings 
		add_settings_section(
				'bxsg_section_slider_shortcode',
				__('Slider Shortcodes', 'bxsg'),
				array( &$this, 'print_section_info_slider_shortcode' ),
				self::$OPTIONS_PAGE_SLUG
			);
    }
	
    /**
     * Save the plugin settings
     * @param array $input The new option values
     * @return 
     */
    public function validate_options( $input ) {		
    	$validated = array();
    	
    	// Build the trusted options array
    	$this->validate_boolean( $input, $validated, self::$OPTION_INCLUDE_CSS );  
    	  
    	$this->validate_boolean( $input, $validated, self::$OPTION_GS_REPLACE_DEFAULT_GALLERIES );    
    	$this->validate_boolean( $input, $validated, self::$OPTION_GS_EXCLUDE_FEATURED_IMAGE );      
    	$this->validate_boolean( $input, $validated, self::$OPTION_GS_HIDE_CAROUSEL );    
    	$this->validate_int( $input, $validated, self::$OPTION_GS_CAROUSEL_THUMB_WIDTH, 1 );
    	$this->validate_int( $input, $validated, self::$OPTION_GS_CAROUSEL_THUMB_MARGIN, 0 );
    	$this->validate_int( $input, $validated, self::$OPTION_GS_CAROUSEL_MIN_THUMBS, 1 );
    	$this->validate_int( $input, $validated, self::$OPTION_GS_CAROUSEL_MAX_THUMBS, $validated[ self::$OPTION_GS_CAROUSEL_MIN_THUMBS ] );
    	$this->validate_int( $input, $validated, 
    			self::$OPTION_GS_CAROUSEL_THUMBS_MOVE, 
	    		0, 
	    		$validated[ self::$OPTION_GS_CAROUSEL_MAX_THUMBS ] );
    	
    	$this->options = $validated;    	
		return $validated;
    }
    
    private function validate_boolean( $input, &$validated, $option_id ) {
    	$validated[ $option_id ] = isset( $input[ $option_id ] ) ? true : false; 
    }
    
    private function validate_int( $input, &$validated, $option_id, $min = null, $max = null ) {
    	// Must be an int
    	if ( !is_int( intval( $input[ $option_id ] ) ) ) {
    		add_settings_error( $option_id, 'settings-errors', 
    			$option_id . ': ' . __( 'must be an integer', 'bxsg' ), 'error' );

    		$validated[ $option_id ] = $this->default_options[ $option_id ];
    		return;
    	}
    	
    	// Must be > min
    	if ( $min!==null && $input[ $option_id ] < $min ) {
    		add_settings_error( $option_id, 'settings-errors', 
    			$option_id . ': ' . sprintf( __( 'must be greater than %s', 'bxsg' ), $min ), 'error' );
    		
    		$validated[ $option_id ] = $this->default_options[ $option_id ];
    		return;
    	}
    	
    	// Must be < max
    	if ( $max!==null && $input[ $option_id ] > $max ) {
    		add_settings_error( $option_id, 'settings-errors', 
    			$option_id . ': ' . sprintf( __( 'must be lower than %s', 'bxsg' ), $max ), 'error' );
    		
    		$validated[ $option_id ] = $this->default_options[ $option_id ];
    		return;
    	}
    	
    	// All good
    	$validated[ $option_id ] = intval( $input[ $option_id ] ); 
    }
	
    /* ------------ SECTIONS OUTPUT --------------------------------------------------------- */
    
    public function print_section_info_general_settings() {
		// echo '<p>' . __( 'Some general plugin options.', 'bxsg' ) . '</p>';
    }
    
    public function print_section_info_gallery_shortcode() {
		echo '<p><em>' . __( 'Options related to the shortcodes that output an image gallery: <code>[gallery]</code> or <code>[bxgallery]</code>.', 'bxsg' ) . '</em></p>';
    }
    
    public function print_section_info_slider_shortcode() {
		echo '<p><em>' . __( 'Options related to the shortcodes that output a generic slider: <code>[slider]</code> or <code>[bxslider]</code>.', 'bxsg' ) . '</em></p>';
    }
	
    /* ------------ FIELDS OUTPUT ----------------------------------------------------------- */
    
    /**
     * Output a text field for a setting
     * 
     * @param string $option_id
     * @param string $type
     */
    public function print_input_field( $args ) {  
    	extract( $args );
    	
    	if ( $type=='checkbox' ) {
    		echo sprintf( '<input type="%s" id="%s" name="%s[%s]" value="open" %s/> %s',
    				esc_attr( $type ),
    				esc_attr( $option_id ),
    				self::$OPTIONS_GROUP,
    				esc_attr( $option_id ),
    				( $this->options[ $option_id ]!=0 ) ? 'checked="checked" ' : '',
					$caption
    		);
    	} else {
			echo sprintf( '<input type="%s" id="%s" name="%s[%s]" value="%s" /> %s', 
					esc_attr( $type ),
					esc_attr( $option_id ),
					self::$OPTIONS_GROUP,
					esc_attr( $option_id ),
					esc_attr( $this->options[ $option_id ] ),
					$caption
				);
    	}
	}
	
    /* ------------ OTHER FUNCTIONS -------------------------------------------------------- */
	
	/**
	 * Load the options (and defaults if the options do not exist yet
	 */
	private function reload_options() {
		$current_options = get_option( BXSG_Settings::$OPTIONS_GROUP );		
		$this->default_options = array(
				self::$OPTION_INCLUDE_CSS 					=> true,
				
				self::$OPTION_GS_REPLACE_DEFAULT_GALLERIES 	=> true,
				self::$OPTION_GS_EXCLUDE_FEATURED_IMAGE 	=> true,
				self::$OPTION_GS_HIDE_CAROUSEL 				=> false,
				self::$OPTION_GS_CAROUSEL_THUMB_WIDTH		=> 60,
				self::$OPTION_GS_CAROUSEL_THUMB_MARGIN		=> 5,
				self::$OPTION_GS_CAROUSEL_MIN_THUMBS		=> 4,
				self::$OPTION_GS_CAROUSEL_MAX_THUMBS		=> 10,
				self::$OPTION_GS_CAROUSEL_THUMBS_MOVE		=> 0,
			);
		
		if ( ! is_array( $current_options ) ) $current_options = array();
		$this->options = array_merge( $this->default_options, $current_options );
		
// 		 echo 'default options:'; var_dump( $this->default_options );
// 		 echo 'current options:'; var_dump( $current_options );
// 		 echo 'final options:'; var_dump( $this->options );
	}
	
	public static $OPTIONS_PAGE_SLUG = 'bxsg-settings';	
	public static $OPTIONS_GROUP = 'bxsg_options';

	// General options
	public static $OPTION_INCLUDE_CSS	 				= 'include_css';
	
	// Gallery shortcodes
	public static $OPTION_GS_REPLACE_DEFAULT_GALLERIES 	= 'gs_replace_default_galleries';
	public static $OPTION_GS_EXCLUDE_FEATURED_IMAGE 	= 'gs_exclude_featured';
	public static $OPTION_GS_HIDE_CAROUSEL 				= 'gs_hide_carousel';
	public static $OPTION_GS_CAROUSEL_THUMB_WIDTH		= 'gs_carousel_thumb_width';
	public static $OPTION_GS_CAROUSEL_THUMB_MARGIN		= 'gs_carousel_thumb_margin';
	public static $OPTION_GS_CAROUSEL_MIN_THUMBS		= 'gs_carousel_min_thumbs';
	public static $OPTION_GS_CAROUSEL_MAX_THUMBS		= 'gs_carousel_max_thumbs';
	public static $OPTION_GS_CAROUSEL_THUMBS_MOVE		= 'gs_carousel_thumbs_move';
	
	
	/** @var BXSG_Plugin The plugin instance */
	private $plugin;
	
	/** @var array */
	private $default_options;
}

endif; // if (!class_exists('BXSG_Settings')) :
