<?php
/*
Plugin Name: WooCommerce Fundraisers
Plugin URI: http://www.jckemp.com
Description: Fundraiser plugin for WooCommerce
Version: 1.0.0
Author: James Kemp
Author Email: support@jckemp.com  
*/

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
    return false;

class jckFundraisers {
   	
    public $name = 'WooCommerce Fundraisers';
    public $shortname = 'Fundraisers';
    public $slug = 'jckf';
    public $version = "1.0.0";
    public $plugin_path;
    public $plugin_url;
    public $cart_data_key;
	
/**	=============================
    *
    * Construct the plugin
    *
    ============================= */
   	
	public function __construct()
	{
		
		$this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->cart_data_key = '_'.$this->slug.'_data';

		// Hook up to the init and plugins_loaded actions
		add_action( 'plugins_loaded',   array( &$this, 'plugins_loaded' ) );
		add_action( 'init',             array( &$this, 'initiate' ) );
		add_action( 'init',             array( 'JCKF_Shortcodes', 'init' ) );
	}

/**	=============================
    *
    * Run quite near the start (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
    
    public function plugins_loaded()
    {
        load_plugin_textdomain( $this->slug, false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
        
        require_once($this->plugin_path.'/inc/class-wc-product-fundraiser.php');
        require_once($this->plugin_path.'/inc/class-jckf-shortcodes.php');
        
        // Filters for cart actions
        // these need to run before init
        add_filter('woocommerce_get_cart_item_from_session',    array(&$this, 'xatik_wc_get_cart_item_from_session'), 10, 3);
        add_action('woocommerce_add_order_item_meta',           array(&$this, 'xatik_wc_order_item_meta'), 10, 2);
        
    }

/**	=============================
    *
    * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
   	
	public function initiate()
	{	
	    
	    add_action( 'woocommerce_before_calculate_totals',          array($this,  'add_custom_price' ) );
	    
	    // Run on admin
        if(is_admin())
        {
            add_filter( 'product_type_selector',                    array( &$this, 'add_product_type') );
            add_filter( 'woocommerce_product_data_tabs',            array( &$this, 'edit_admin_product_tabs') );
            add_action( 'woocommerce_product_write_panels',         array( &$this, 'admin_product_tab_content' ) );
            add_action( 'woocommerce_process_product_meta',         array( &$this, 'process_product_tabs' ), 10, 2 );
            add_action( 'admin_enqueue_scripts',                    array( &$this, 'product_edit_page_scripts' ), 10, 1 );
            
            // Filters for cart actions
            // This is loaded via ajax usually, so needs to be run in admin
            add_filter( 'woocommerce_cart_item_price',              array(&$this, 'cart_item_price'), 10, 3 );
        }
        
        // Run on frontend
        else
        {
            add_filter( 'woocommerce_locate_template',              array( &$this, 'template_override'), 10, 3 );
            add_action( 'woocommerce_fundraiser_add_to_cart',       array( &$this, 'woocommerce_fundraiser_add_to_cart'), 30 );            
            add_action( 'woocommerce_add_to_cart',                  array( &$this, 'add_to_cart_hook') );
            add_filter( 'woocommerce_is_sold_individually',         array( &$this, 'sold_individually'), 10, 2 );
            add_filter( 'woocommerce_product_tabs',                 array( &$this, 'edit_product_tabs'), 98 );
            add_action( 'wp_enqueue_scripts',                       array( &$this, 'scripts') );
            add_action( 'wp_enqueue_scripts',                       array( &$this, 'styles') );
            
            // Filters for cart actions
            add_filter( 'woocommerce_add_cart_item_data',           array(&$this, 'xatik_wc_add_cart_item_data'), 10, 2);
            add_filter( 'woocommerce_get_item_data',                array(&$this, 'xatik_wc_get_item_data'), 10, 2);    
        }
        
	}
	
    /* ----------------------------------------------------------------------------------- */
    /* Filters for cart actions */
    /* ----------------------------------------------------------------------------------- */

    function xatik_wc_add_cart_item_data($cart_item_data, $product_id) {
        global $woocommerce;
        
        $product = get_product( $product_id );

        if ($product && $product->product_type == "fundraiser") {
            $cart_item_data[$this->cart_data_key]['reward'] = ( isset( $_POST['reward'] ) && $_POST['reward'] != '') ? $_POST['reward'] : '';
        }

        return $cart_item_data;
    }
    
    function xatik_wc_get_cart_item_from_session( $item, $values, $key ) {
        
        if ( array_key_exists( $this->cart_data_key, $values ) )
            $item[$this->cart_data_key] = $values[$this->cart_data_key];
            
        return $item;
    }
    
    function xatik_wc_get_item_data($other_data, $cart_item) {
        
        if (isset($cart_item[$this->cart_data_key])) {

            $data = $cart_item[$this->cart_data_key];

            // Add custom data to product data
            if($data['reward'] != "")
                $other_data[] = array('name' => 'Reward', 'value' => $data['reward']);
        }

        return $other_data;
    }

    function xatik_wc_order_item_meta($item_id, $cart_item) {
        if ( isset($cart_item[$this->cart_data_key]['reward']) && $cart_item[$this->cart_data_key]['reward'] != "" ) 
        { 
            wc_add_order_item_meta( $item_id, __( 'Reward'), $cart_item[$this->cart_data_key]['reward'] ); 
        }
    }
    
    public function cart_item_price($price, $cart_item, $cart_item_key)
    {    
        global $woocommerce;
        
        $named_price = $woocommerce->session->__get($cart_item_key.'_named_price');
        
        return ($named_price) ? wc_price($named_price) : $price;
    }

/**	=============================
    *
    * Product page admin scripts
    *
    * @param array $hook Current page hook
    *
    ============================= */

    public function product_edit_page_scripts($hook)
    {
        global $post;
        
        // If we're not on the post edit page, and post type is not equal
        // to product, don't enqueue anything
        
        if ( 'post.php' != $hook && ($post && $post->post_type != "product") ) {
            return;
        }
        
        // Otherwise, enqueue this!
        
        wp_enqueue_script( $this->slug.'_admin_scripts', $this->plugin_url . '/assets/admin/js/jckf-scripts.min.js', array(), $this->version );
    }
    
/**	=============================
    *
    * Product page scripts
    *
    ============================= */
    
    public function scripts()
    {
        global $post;
        
        $product = get_product($post->ID);
        
        if($product && $product->product_type == "fundraiser"):
        
            wp_enqueue_script( $this->slug.'_scripts', $this->plugin_url . '/assets/frontend/js/jckf-scripts.min.js', array(), $this->version, true );
        
        endif;
    }

/**	=============================
    *
    * Product page styles
    *
    ============================= */
    
    public function styles()
    {
        global $post;
        
        $product = get_product($post->ID);
        
        if($product && $product->product_type == "fundraiser"):
        
            wp_enqueue_style( $this->slug.'_styles', $this->plugin_url . '/assets/frontend/css/jckf-styles.min.css', array(), $this->version );
        
        endif;
    }

/**	=============================
    *
    * Add the "Fundraiser" product type to the edit/add product dropdown
    *
    * @param array $types Current types of products
    *
    ============================= */
	
	public function add_product_type( $types )
	{
        $types[ 'fundraiser' ] = __( 'Fundraiser' );
        return $types;
    }

/**	=============================
    *
    * Edit product tabs
    *
    * We're adding and editing the tabs which show when "Fundraiser" is
    * the product type.
    *
    * @param array $tabs All tabs
    *
    ============================= */
   	
   	public function edit_admin_product_tabs( $tabs )
   	{
       	$tabs['shipping']['class'][] = 'hide_if_fundraiser';
       	$tabs['attribute']['class'][] = 'hide_if_fundraiser';
       	
       	$tabs['goal'] = array(
            'label'  => __( 'Goal', $this->slug ),
            'target' => 'fundraiser_goal_product_data',
            'class'  => array( 'show_if_fundraiser' )
       	);
       	
       	$tabs['fundraiser_type'] = array(
            'label'  => __( 'Rewards', $this->slug ),
            'target' => 'fundraiser_rewards_product_data',
            'class'  => array( 'show_if_fundraiser' )
       	);
       	
       	return $tabs;
   	}
   	
/**	=============================
   	*
   	* Product tab content
	*
	* Display the tab content for the new tabs we added in edit_admin_product_tabs()
   	*
   	============================= */   	

   	public function admin_product_tab_content()
	{
		global $post;
		
		$fundData = get_post_meta($post->ID, $this->slug, true);
		
		require_once($this->plugin_path.'/inc/admin-tab-goal.php');
		
		require_once($this->plugin_path.'/inc/admin-tab-rewards.php');
	}
	
/**	=============================
   	*
   	* Save the new product tab content from product_tab_content()
   	*
   	============================= */
	
	public function process_product_tabs( $post_id )
	{   
	    $goal = (isset($_POST[$this->slug]['goal'])) ? $_POST[$this->slug]['goal'] : false;
	    $rewards = (isset($_POST[$this->slug]['rewards'])) ? $_POST[$this->slug]['rewards'] : false;
	    
	    $fundData = array(
	        'goal' => $goal,
	        'rewards' => $rewards
	    );
	    
		update_post_meta( $post_id, $this->slug, $fundData);
	}

/**	=============================
    * Output the fundraiser product add to cart area.
    *
    * @access public
    * @return void
    ============================= */
	
	public function woocommerce_fundraiser_add_to_cart() {
		wc_get_template( 'single-product/add-to-cart/fundraiser.php' );
	}
	
/**	=============================
    * Use the price entered by the user.
    *
    * @access public
    * @return void
    ============================= */	
	
	public function add_custom_price( $cart_object ) {
        global $woocommerce;
        foreach ( $cart_object->cart_contents as $key => $value ) {
            
            if($value['data']->product_type !== 'fundraiser')
            {
              continue;
            }
            
            $named_price = $woocommerce->session->__get($key .'_named_price');
            if($named_price)
            {
                $value['data']->set_price($named_price);
            }
        }
    }

/**	=============================
    * Set the price when adding to cart and add to session
    *
    * @access public
    * @return void
    ============================= */
    
    public function add_to_cart_hook($key)
    {
        global $woocommerce;
        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) 
        {
          
            if($values['data']->product_type !== "fundraiser")
            {
                $values['data']->set_price($_POST['price']);
                continue;
            }
            
            $thousands_sep  = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ), ENT_QUOTES );
            $decimal_sep = stripslashes( get_option( 'woocommerce_price_decimal_sep' ) );
            $_POST['price'] = str_replace($thousands_sep, '', $_POST['price']);
            $_POST['price'] = str_replace($decimal_sep, '.', $_POST['price']);
            
            $_POST['price'] = wc_format_decimal($_POST['price']);
            
            if($cart_item_key == $key)
            {
                $values['data']->set_price($_POST['price']);
                $woocommerce->session->__set($key .'_named_price', $_POST['price']);
            }
        }

    return $key;
    }

/**	=============================
   	*
   	* Edit tabs for the fundraiser product page
   	*
   	============================= */
 
    function edit_product_tabs( $tabs )
    {
        global $product, $post;
        
        if($product->product_type == "fundraiser"):
            // Remove the reviews tab
            
            unset( $tabs['reviews'] );
            
            $rewards = $product->get_rewards();

            if($rewards):
                
                // Adds new tab for rewards
    	
            	$tabs[$this->slug.'-rewards'] = array(
            		'title' 	=> __( 'Rewards', 'woocommerce' ),
            		'priority' 	=> 50,
            		'callback' 	=> array( &$this, 'rewards_product_tab_content' )
            	);
        	
        	endif;
        	
    	endif;
     
        return $tabs;
     
    }

/**	=============================
   	*
   	* Edit tabs for the fundraiser product page
   	*
   	============================= */
    
    public function rewards_product_tab_content()
    {        
        wc_get_template( 'single-product/fundraisers/rewards.php' );
    }
    
/**	=============================
   	*
   	* Only allow fundraiser products to be added once
   	*
   	============================= */
   	
    public function sold_individually($return, $product)
    {
        if($product->product_type == "fundraiser")
        {
            return( true );
        }
    }
	
/**	=============================
   	*
   	* Modification: Get the template from this plugin, if it exists
   	* Works for any woocommerce template
   	*
   	============================= */
   	
   	public function template_override($template, $template_name, $template_path ) {
   	
        $plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) ). '/templates/';
        
        if ( file_exists( $plugin_path . $template_name ) )
        {
          $template = $plugin_path . $template_name;
          return $template;
        }
    
        return $template;

    }
  
}

$jckFundraisers = new jckFundraisers();