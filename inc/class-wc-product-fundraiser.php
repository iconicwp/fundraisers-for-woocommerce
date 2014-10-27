<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Fundraiser Product Class
 *
 * Fundraiser product type.
 *
 * @class 		WC_Product_Fundraiser
 * @version		1.0.0
 * @category	Class
 * @author 		James Kemp
 */
 
if(class_exists('WC_Product')):
    class WC_Product_Fundraiser extends WC_Product {
    
    /**	=============================
       	*
       	* __construct function.
    	*
       	* @access public
        * @param mixed $product
       	*
       	============================= */
    
    	public function __construct( $product ) {
    		$this->product_type = 'fundraiser';
    		parent::__construct( $product );
    	}

    /**	=============================
       	*
       	* Change Add to Cart Button text
    	*
        * @return string
       	*
       	============================= */
    	
    	public function single_add_to_cart_text() {
            return __( 'Donate', 'woocommerce' );
        }
    
    /**	=============================
       	*
       	* Get the add to url used mainly in loops.
    	*
       	* @access public
        * @return string
       	*
       	============================= */
    	
    	public function add_to_cart_url() {
    		$url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );
    		
    		//$url = 'hello';
    
    		return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
    	}
    
    /**	=============================
       	*
       	* Returns false if the product cannot be bought.
    	*
       	* @access public
       	* @return bool
       	*
       	============================= */
    	
    	public function is_purchasable()
    	{
    		return apply_filters( 'woocommerce_is_purchasable', true, $this );
    	}
    
    /**	=============================
       	*
       	* Returns formatted product fund data
    	*
       	* @access public
       	* @return array
       	*
       	============================= */
       	
       	public function get_fund_data()
       	{
           	global $post, $product, $jckFundraisers;
           	
           	$return = false;
           	
           	if($product->product_type == "fundraiser"):
           	 
           	    $return = array(
           	        'rewards' => false,
           	        'goal' => false
           	    );
           	
           	    $fundData = get_post_meta($post->ID, $jckFundraisers->slug, true);
                   
                $return['goal'] = (isset($fundData['goal'])) ? $fundData['goal'] : false;
                $return['rewards'] = (isset($fundData['rewards'])) ? $fundData['rewards']['rewards'] : false;
                
           	endif;
           	
           	return $return;
       	}
    
    /**	=============================
       	*
       	* Returns html for total donations count
    	*
       	* @access public
       	* @return string
       	*
       	============================= */
       	
       	public function get_total_donations_html()
       	{
       	    global $jckFundraisers;
       	 
       	    $salesData = $this->get_sales_data();
       	    
       	    $totalDonationsHtml = '<p>' . sprintf( _n( '1 donation', '%s donations', $salesData['total_processed_sales'], $jckFundraisers->slug ), $salesData['total_processed_sales'] ) . '</p>';
       	    
       	    return apply_filters( $jckFundraisers->slug.'_total_donations', $totalDonationsHtml );
       	}
    
    /**	=============================
       	*
       	* Returns html for total raised value
    	*
       	* @access public
       	* @return string
       	*
       	============================= */
       	
       	public function get_total_raised_html()
       	{
       	    global $jckFundraisers;
       	 
       	    $salesData = $this->get_sales_data();
       	    $fundData = $this->get_fund_data();
       	    
           	$totalRaised = wc_price($salesData['total_raised']);
            $goalAmount = wc_price($fundData['goal']['amount']);
            
            // @todo - Check if there is a monetary goal. if not, don't show the "of %s goal" part
            
            $totalRaisedHtml = '<p>' . sprintf( __( '%s raised of %s goal', $jckFundraisers->slug ), $totalRaised, $goalAmount ) . '</p>';
            
            return apply_filters( $jckFundraisers->slug.'_total_raised', $totalRaisedHtml, $totalRaised, $goalAmount );
       	}
    
    /**	=============================
       	*
       	* Returns html for days remaining
    	*
       	* @access public
       	* @return string
       	*
       	============================= */
       	
       	public function get_days_remaining_html()
       	{
       	    global $jckFundraisers;
       	    
       	    $fundData = $this->get_fund_data();
       	    
           	$daylen = 60*60*24;
                
            $now = date('Y-m-d');
            $target = $fundData['goal']['end'];
            
            $daysRemaining = (strtotime($target)-strtotime($now))/$daylen;
            
            $daysRemainingHtml = '<p>' .  sprintf( _n( '1 day to go', '%s days to go', $daysRemaining, $jckFundraisers->slug ), $daysRemaining ) . '</p>';
            
            return apply_filters( $jckFundraisers->slug.'_days_remaining', $daysRemainingHtml, $daysRemaining );
       	}

    /**	=============================
       	*
       	* Returns an array of awards, or false if there are none
    	*
       	* @return array|bool
       	*
       	============================= */
       	
       	public function get_rewards()
       	{
           	global $post, $jckFundraisers;

            $fundData = get_post_meta($post->ID, $jckFundraisers->slug, true);
            $rewardsType = (isset($fundData['rewards'])) ? $fundData['rewards']['type'] : false;
            
            if($rewardsType == "rewards") {
                $noRewards = array(
                    'unique' => '',
                    'amount' => '',
                    'description' => 'No Reward.',
                    'limit' => '',
                    'delivery' => ''
                );
                $rewards = (isset($fundData['rewards'])) ? $fundData['rewards']['rewards'] : array();
                
                array_unshift($rewards, $noRewards);
                
                return $rewards;
            }
            
            return false;
       	}
    
    /**	=============================
       	*
       	* Returns the fundraiser sales data
    	*
       	* @access public
       	* @return array
       	*
       	============================= */
    	
    	public function get_sales_data() {
        	global $wpdb;
                    
            $orderItemMataClean = array();
            
            // Get all order item meta when it relates to the same ID
            // as the current fundraiser product
            
            $orderItemMetaSql = $wpdb->get_results($wpdb->prepare(
                    
                "
                SELECT order_item_id, meta_key, meta_value
                FROM {$wpdb->prefix}woocommerce_order_itemmeta
                WHERE order_item_id IN (SELECT order_item_id from {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key = '_product_id' AND meta_value = '%d')
            	"
            
            , $this->id));
            
            // loop through the results and format it into
            // a better array
            
            if($orderItemMetaSql):
                foreach($orderItemMetaSql as $orderItemMetaSqlArr):
                    
                    $orderItemMataClean[$orderItemMetaSqlArr->order_item_id][$orderItemMetaSqlArr->meta_key] = $orderItemMetaSqlArr->meta_value;
                    
                endforeach;
            endif;
            
            // loop through the order item meta we just got and get the respective order 
            // ids. then we'll check whether they are complete and adjust our 
            // $orderItemMataClean array to include only complete/processed items
            
            if(!empty($orderItemMataClean)):
                foreach($orderItemMataClean as $orderItemId => $orderItemMeta):
                    
                    $orderId = $wpdb->get_row($wpdb->prepare(
                    
                        "
                        SELECT order_id
                        FROM {$wpdb->prefix}woocommerce_order_items
                        WHERE order_item_id = '%d'
                    	"
                    
                    , $orderItemId));
                    
                    if($orderId):
                    
                        $orderId = $orderId->order_id;
                        $orderStatus = get_post_status($orderId);
                        
                        if($orderStatus == "wc-processing" || $orderStatus == "wc-completed"):
                        
                            $orderItemMataClean[$orderItemId]['_order_id'] = $orderId;
                            $orderItemMataClean[$orderItemId]['_order_status'] = $orderStatus;
                        
                        else:
                        
                            unset($orderItemMataClean[$orderItemId]);
                        
                        endif;
                        
                    else:
                        
                        unset($orderItemMataClean[$orderItemId]);
                    
                    endif;
                    
                endforeach;
            endif;
            
            // $orderItemMataClean now contains an array of order item meta for only complete orders
            // for this particular product id
            
            // Now we just need to add up the line totals for all completed/processing orders
            
            $totalRaised = 0;
            
            if(!empty($orderItemMataClean)):
                foreach($orderItemMataClean as $orderItemId => $orderItemMeta):
                
                    $totalRaised = $totalRaised+$orderItemMeta['_line_total'];
                    
                endforeach;
            endif;
            
            return array(
                'total_raised' => $totalRaised,
                'total_processed_sales' => count($orderItemMataClean)
            );
    	}
    }
endif;