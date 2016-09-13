<?php
/*
Plugin Name: Fundraisers for WooCommerce
Plugin URI: https://iconicwp.com
Description: Raise funds and offer rewards for any event using your WooCommerce store.
Version: 1.0.5
Author: James Kemp
Author Email: support@jckemp.com
*/

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
    return false;

class jckFundraisers {

    public $name = 'WooCommerce Fundraisers';
    public $shortname = 'Fundraisers';
    public $slug = 'jckf';
    public $version = "1.0.5";
    public $plugin_path;
    public $plugin_url;
    public $cart_data_key;

/**	=============================
    *
    * Construct the plugin
    *
    ============================= */

	public function __construct() {

		$this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->cart_data_key = '_'.$this->slug.'_data';

        require_once( $this->plugin_path.'inc/admin/vendor/class-dashboard.php' );

		// Hook up to the init and plugins_loaded actions
		add_action( 'plugins_loaded',   array( $this, 'plugins_loaded' ) );
		$this->initiate();
	}

/**	=============================
    *
    * Run quite near the start (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */

    public function plugins_loaded() {
        load_plugin_textdomain( $this->slug, false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

        require_once($this->plugin_path.'/inc/class-wc-product-fundraiser.php');

        // Filters for cart actions
        // needs to run earlier than plugin init
        add_filter('woocommerce_get_cart_item_from_session',    array( $this, 'get_cart_item_from_session' ), 10, 3);
        add_action('woocommerce_add_order_item_meta',           array( $this, 'add_order_item_meta' ), 10, 2);
    }

/**	=============================
    *
    * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */

	public function initiate() {

	    add_action( 'woocommerce_before_calculate_totals',              array( $this,  'add_custom_price' ) );

	    // Run on admin
        if(is_admin()) {
            add_filter( 'product_type_selector',                        array( $this, 'add_product_type' ) );
            add_filter( 'woocommerce_product_data_tabs',                array( $this, 'edit_admin_product_tabs' ) );
            add_action( 'woocommerce_product_write_panels',             array( $this, 'admin_product_tab_content' ) );
            add_action( 'woocommerce_process_product_meta_fundraiser',  array( $this, 'process_product_tabs' ), 10, 2 );
            add_action( 'admin_enqueue_scripts',                        array( $this, 'product_edit_page_scripts' ), 10, 1 );
            add_action( 'admin_menu',                                   array( $this, 'add_admin_pages' ) );

            // Filters for cart actions
            // This is loaded via ajax usually, so needs to be run in admin
            add_filter( 'woocommerce_cart_item_price',                  array( $this, 'cart_item_price'), 10, 3 );

        } else {

            add_filter( 'woocommerce_locate_template',                  array( $this, 'template_override' ), 10, 3 );
            add_action( 'woocommerce_fundraiser_add_to_cart',           array( $this, 'woocommerce_fundraiser_add_to_cart' ), 30 );
            add_action( 'woocommerce_add_to_cart',                      array( $this, 'add_to_cart_hook' ) );
            add_filter( 'woocommerce_product_tabs',                     array( $this, 'edit_product_tabs' ), 98 );
            add_action( 'wp_enqueue_scripts',                           array( $this, 'scripts' ) );
            add_action( 'wp_enqueue_scripts',                           array( $this, 'styles' ) );

            add_action( 'woocommerce_single_product_summary',           array( $this, 'fundraiser_statistics_summary' ), 15 );

            // Filters for cart actions
            add_filter( 'woocommerce_add_cart_item_data',               array( $this, 'add_cart_item_data' ), 10, 2);
            add_filter( 'woocommerce_get_item_data',                    array( $this, 'get_item_data' ), 10, 2);
            add_action( 'woocommerce_add_to_cart_validation',           array( $this, 'validate_donation' ), 1, 3 );
        }

	}

/**	=============================
    *
    * Add Admin Pages
    *
    * @type admin
    * @access public
    *
    ============================= */

	public function add_admin_pages() {
    	add_submenu_page( 'woocommerce', __('Donations', $this->slug), __('Donations', $this->slug), 'manage_woocommerce', $this->slug.'-donations', array( $this, 'donations_list' ) );
	}

/**	=============================
    *
    * Donations Page
    *
    * This page lists all the donations that have been made,
    * and allows the admin to see which rewards need to be
    * shipped
    *
    * @type admin
    * @access public
    *
    ============================= */

    public function donations_list() {
        if ( !current_user_can( 'manage_woocommerce' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', $this->slug ) );
		}

		require_once($this->plugin_path.'/inc/admin-page-donations.php');
    }

/**	=============================
    *
    * Product page admin scripts
    *
    * @type admin
    * @access public
    * @param array $hook Current page hook
    *
    ============================= */

    public function product_edit_page_scripts($hook) {
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
    * Fundraiser Product page scripts
    *
    * @type frontend
    * @access public
    *
    ============================= */

    public function scripts() {
        global $post;

        $product = get_product($post->ID);

        if($product && $product->product_type == "fundraiser"):

            wp_register_script( 'magnific-popup', $this->plugin_url . '/assets/frontend/vendor/jquery.magnific-popup.min.js', array('jquery'), $this->version, true );
            wp_register_script( 'jckf-scripts', $this->plugin_url . '/assets/frontend/js/main.min.js', array('magnific-popup', 'jquery'), $this->version, true );

            wp_enqueue_script( 'magnific-popup' );
            wp_enqueue_script( 'jckf-scripts' );

        endif;
    }

/**	=============================
    *
    * Fundraiser Product page styles
    *
    * @access public
    *
    ============================= */

    public function styles() {
        global $post;

        $product = get_product($post->ID);

        if($product && $product->product_type == "fundraiser"):

            wp_register_style( 'magnific-popup', $this->plugin_url . '/assets/frontend/vendor/magnific-popup.css', array(), $this->version );
            wp_register_style( 'jckf-styles', $this->plugin_url . '/assets/frontend/css/main.min.css', array('magnific-popup'), $this->version );

            wp_enqueue_style( 'magnific-popup' );
            wp_enqueue_style( 'jckf-styles' );

        endif;
    }

/**	=============================
    *
    * Add the "Fundraiser" product type to the edit/add product dropdown
    *
    * @access public
    * @param array $types Current types of products
    *
    ============================= */

	public function add_product_type( $types ) {
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
    * @param arr $tabs All tabs
    * @return arr
    *
    ============================= */

    public function edit_admin_product_tabs( $tabs ) {
       	$tabs['shipping']['class'][] = 'hide_if_fundraiser';

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

    public function admin_product_tab_content() {
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

    public function process_product_tabs( $post_id ) {
        $goal = (isset($_POST[$this->slug]['goal'])) ? $_POST[$this->slug]['goal'] : false;
        $rewardData = (isset($_POST[$this->slug]['rewards'])) ? $_POST[$this->slug]['rewards'] : false;

        // check if goal end date is valid
        if($goal['end'] != "" && !$this->validate_date($goal['end'])):

            WC_Admin_Meta_Boxes::add_error( __( 'Please enter a valid goal end date (YYYY-MM-DD).', $this->slug ) );
            return;

        endif;

        if(is_array($rewardData) && isset($rewardData['rewards']) && $rewardData['type'] != "no_rewards"):

            // check if each reward has a unique ID
            $rewardIds = array();

            foreach($rewardData['rewards'] as $reward):

                // check if the reward ID is blank
                // if it is, throw an error
                if(trim($reward['unique']) == ""):

                    WC_Admin_Meta_Boxes::add_error( __( 'Please enter a unique ID for each reward.', $this->slug ) );
                    return;

                endif;

                // check if the reward ID is in our array
                if(!in_array($reward['unique'], $rewardIds)):

                    $rewardIds[] = $reward['unique'];

                // if the reward ID is in the array, don't
                // save the data and add an error
                else:

                    WC_Admin_Meta_Boxes::add_error( __( 'Sorry, each reward ID must be unique.', $this->slug ) );
                    return;

                endif;

                // check if the date is valid
                if($reward['delivery'] != "" && !$this->validate_date($reward['delivery'])):

                    WC_Admin_Meta_Boxes::add_error( __( 'Please enter a valid estimated delivery date (YYYY-MM-DD).', $this->slug ) );
                    return;

                endif;

            endforeach;

        endif;

        $fundData = array(
            'goal' => $goal,
            'rewards' => $rewardData
        );

        update_post_meta( $post_id, $this->slug, $fundData);
    }

/**	=============================
    *
    * Validate Date
    *
    * Check if a date is in YYYY-MM-DD format
    *
    * @access public
    * @return bool
    *
    ============================= */

    public function validate_date($date) {
        $date = explode('-', $date);

        $day = (int)$date[2];
        $month = (int)$date[1];
        $year = (int)$date[0];

        if(!is_array($date) || count($date) != 3)
            return false;

        if(!is_int($day) || !is_int($month) || !is_int($year))
            return false;

        return checkdate( $month, $day, $year);
    }

/**	=============================
    *
    * Output the fundraiser product add to cart area.
    *
    * @access public
    * @return void
    *
    ============================= */

	public function woocommerce_fundraiser_add_to_cart() {
		wc_get_template( 'single-product/add-to-cart/fundraiser.php' );
	}

/**	=============================
    *
    * Edit tabs for the fundraiser product page
    *
    ============================= */

    function edit_product_tabs( $tabs ) {
        global $product, $post;

        if($product->product_type == "fundraiser"):

            // Remove the reviews tab
            unset( $tabs['reviews'] );

            $rewards = $product->get_rewards();

            if($rewards && $product->is_purchasable()):

                // Adds new tab for rewards
                $tabs[$this->slug.'-rewards'] = array(
                    'title' 	=> __( 'Rewards', 'woocommerce' ),
                    'priority' 	=> 50,
                    'callback' 	=> array( $this, 'rewards_product_tab_content' )
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

    public function rewards_product_tab_content() {
        wc_get_template( 'single-product/fundraisers/rewards.php' );
    }

/**	=============================
    *
    * Add Cart Item Data
    *
    * This is run when a product is added to the cart,
    * it will add additional data to our fundraiser product
    * i.e. the reward unique ID for displaying in the cart
    * and at checkout
    *
    * @access public
    * @param obj $cart_item_data
    * @param int $product_id
    *
    ============================= */

    public function add_cart_item_data($cart_item_data, $product_id) {
        global $woocommerce;

        $product = get_product( $product_id );

        if ($product && $product->product_type == "fundraiser") {
            $cart_item_data[$this->cart_data_key]['reward'] = ( isset( $_POST['reward'] ) && $_POST['reward'] != '') ? $_POST['reward'] : '';
        }

        return $cart_item_data;
    }

/**	=============================
    *
    * Get Cart Item from Session
    *
    * When the cart is collected from the session, we will check if
    * the fundraiser data is set, then add it to the session, too
    *
    * @access public
    * @param arr $item Item data
    * @param arr $values Item values from the cart
    * @param str $key Item key from the cart
    *
    ============================= */

    public function get_cart_item_from_session( $item, $values, $key ) {

        if ( array_key_exists( $this->cart_data_key, $values ) )
            $item[$this->cart_data_key] = $values[$this->cart_data_key];

        return $item;
    }

/**	=============================
    *
    * Get Cart Item Data
    *
    * When the cart item is displayed on the frontend, this
    * function is run to add our additional reward data in the cart
    *
    * @access public
    * @param arr $other_data
    * @param obj $cart_item
    *
    ============================= */

    public function get_item_data($other_data, $cart_item) {

        if (isset($cart_item[$this->cart_data_key])) {
            $data = $cart_item[$this->cart_data_key];

            // Add custom data to product data
            if($data['reward'] != "") {

                $product = get_product($cart_item['product_id']);
                $reward = $product->get_reward($data['reward']);

                if($reward)
                    $other_data[] = array('name' => 'Reward', 'value' => $reward['description']);
            }
        }

        return $other_data;
    }

/**	=============================
    *
    * Add meta to the item on an order
    *
    * When the order is created, this function is run and
    * allows us to add the reward data to the product so it
    * is visible on the final order
    *
    * @type frontend
    * @access public
    * @param int $item_id
    * @param obj $cart_item
    *
    ============================= */

    public function add_order_item_meta($item_id, $cart_item) {
        if ( isset($cart_item[$this->cart_data_key]['reward']) && $cart_item[$this->cart_data_key]['reward'] != "" ) {
            $data = $cart_item[$this->cart_data_key];

            $product = get_product($cart_item['product_id']);
            $reward = $product->get_reward($data['reward']);

            wc_add_order_item_meta( $item_id, __( 'Reward ID', $this->slug), $data['reward'] );
            wc_add_order_item_meta( $item_id, __( 'Reward', $this->slug), $reward['description'] );
        }
    }

/**	=============================
    *
    * Alter the fundraiser price in the cart widget
    *
    * @type admin/ajax
    * @access public
    * @param int $price
    * @param obj $cart_item
    * @param obj $cart_item_key
    *
    ============================= */

    public function cart_item_price($price, $cart_item, $cart_item_key) {
        global $woocommerce;

        $donate_price = $woocommerce->session->__get($cart_item_key.'_donate_price');

        return ($donate_price) ? wc_price($donate_price) : $price;
    }

/**	=============================
    *
    * Alter the fundraiser price in the cart widget
    *
    * @type frontend
    * @access public
    * @param int $price
    * @param obj $cart_item
    * @param obj $cart_item_key
    * @return bool
    *
    ============================= */

    public function validate_donation( $passed, $product_id, $quantity ) {
        global $woocommerce;

        $product = get_product( $product_id );

        if( $product->product_type == "fundraiser" ) {

            // validate donation amount
            if( isset($_POST['price']) && $_POST['price'] <= 0 )
            {
                wc_add_notice( __( "Please enter a valid donation amount.", $this->slug ), 'error' );
                return false;
            }

            // check if donation amount allows for selected reward
            if( isset($_POST['price']) && ( isset($_POST['reward']) && $_POST['reward'] != "" ) )
            {
                $product = get_product( $product_id );
                $theReward = $product->get_reward($_POST['reward']);

                if($theReward)
                {

                    if($_POST['price'] < $theReward['amount'])
                    {
                        wc_add_notice(
                            sprintf(
                                __( "Your selected reward requires a donation of at least %s.", $this->slug ),
                                wc_price($theReward['amount'])
                            ),
                            'error'
                        );
                        return false;
                    }

                }
                else
                {

                    wc_add_notice( __( "Please choose a valid reward.", $this->slug ), 'error' );
                    return false;

                }
            }

            // check that there is only 1 of this item in the cart
    		$woocommerce_max_qty = 1;
    		$already_in_cart = $this->get_qty_alread_in_cart( $product_id );

    		if ( ! empty( $already_in_cart ) )
    		{
    			// there was already a quantity of this item in cart prior to this addition
    			// Check if the total of $already_in_cart + current addition quantity is more than our max
    			$new_qty = $already_in_cart + $quantity;
    			if ( $new_qty > $woocommerce_max_qty )
    			{
    				// oops. too much.
    				$product = get_product( $product_id );
    				$product_title = $product->post->post_title;

    				wc_add_notice( __( "Sorry, you can only donate once.", $this->slug ), 'error' );

    				$passed = false;
    			} else {
    				// addition qty is okay
    				$passed = true;
    			}
    		} else {
    			// none were in cart previously, and we already have input limits in place, so no more checks are needed
    			$passed = true;
    		}

		}

		return $passed;
    }

/**	=============================
    *
    * Use the price entered by the user.
    *
    * @access public
    * @return void
    *
    ============================= */

	public function add_custom_price( $cart_object ) {
        global $woocommerce;

        foreach ( $cart_object->cart_contents as $key => $value ) {

            if($value['data']->product_type !== 'fundraiser')
            {
              continue;
            }

            $donate_price = $woocommerce->session->__get($key.'_donate_price');
            if($donate_price)
            {
                $value['data']->set_price($donate_price);
            }
        }
    }

/**	=============================
    *
    * Set the price when adding to cart and add to session
    *
    * @access public
    * @return void
    *
    ============================= */

    public function add_to_cart_hook($key) {
        global $woocommerce;

        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {

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
                $woocommerce->session->__set($key.'_donate_price', $_POST['price']);
            }
        }

        return $key;
    }

/**	=============================
    *
    * Check how many of an item is in the cart
    *
    * @type helper
    * @access public
    * @param int $the_id ID of the product (variable or simple)
    *
    ============================= */

    public function get_qty_alread_in_cart( $the_id ) {
		global $woocommerce;

		$qty = 0;

		// search the cart for the product in question
		foreach($woocommerce->cart->get_cart() as $cart_item_key => $values )
		{
			if ( $the_id == $values['product_id'] )
			{
				// this is the product in question, get its qty
				$qty = $values['quantity'];
			}
		}

		return $qty;
	}

/**	=============================
    *
    * Add stats of the fundraiser before the product summary
    *
    * @type frontend
    * @access public
    * @return str
    *
    ============================= */

	public function fundraiser_statistics_summary() {
    	global $product;

    	if($product->product_type == "fundraiser"):

        	$fundData = $product->get_fund_data();
        	$goalData = $fundData['goal'];

        	$donations = $product->get_total_donations_html();
        	$raised = $product->get_total_raised_html();
        	$daysRemaining = ($goalData['type'] == 'target_date' || $goalData['type'] == 'target_goal_date') ? $product->get_days_remaining_html() : '';

        	echo sprintf(
            	'<div class="'.$this->slug.'-stats">%s %s %s</div>',
            	$donations,
            	$raised,
            	$daysRemaining
        	);

    	endif;
	}

/**	=============================
    *
    * Modification: Get the template from this plugin, if it exists
    * Works for any woocommerce template
    *
    ============================= */

    public function template_override($template, $template_name, $template_path ) {

        $plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) ). '/templates/';

        if ( file_exists( $plugin_path . $template_name ) ) {
            $template = $plugin_path . $template_name;
            return $template;
        }

        return $template;
    }

}

$jckFundraisers = new jckFundraisers();