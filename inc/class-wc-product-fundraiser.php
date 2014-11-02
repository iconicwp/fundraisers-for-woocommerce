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
        	$purchasable = true;
        	
        	$fundData = $this->get_fund_data();
        	$goalData = $fundData['goal'];
        	$salesData = $this->get_sales_data();
        	
        	if($goalData['type'] == 'target_date' || $goalData['type'] == 'target_goal_date')
        	{
            	if($this->get_days_remaining() < 0) $purchasable = false;
        	}
        	elseif($goalData['type'] == 'target_goal')
        	{
            	if($salesData['total_raised'] >= $goalData['amount']) $purchasable = false;
        	}
        	
    		return apply_filters( 'woocommerce_is_purchasable', $purchasable, $this );
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
           	global $jckFundraisers;
           	
           	$return = false;
           	
           	if($this->product_type == "fundraiser"):
           	 
           	    $return = array(
           	        'rewards' => false,
           	        'goal' => false
           	    );
           	
           	    $fundData = get_post_meta($this->id, $jckFundraisers->slug, true);
                   
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
       	    
       	    $totalDonationsHtml = sprintf( 
       	       '<p class="'.$jckFundraisers->slug.'-stat '.$jckFundraisers->slug.'-stat--total-donations"><strong>%s</strong> %s</p>',
       	       $salesData['total_processed_sales'],
       	       _n( 'donation', 'donations', $salesData['total_processed_sales'], $jckFundraisers->slug )
       	    );
       	    
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
            
            $totalRaisedHtml = sprintf(
                '<p class="'.$jckFundraisers->slug.'-stat '.$jckFundraisers->slug.'-stat--total-raised"><strong>%s</strong> %s %s</p>',
                $totalRaised,
                __('raised', $jckFundraisers->slug),
                sprintf( __('of %s goal', $jckFundraisers->slug), $goalAmount)
            );
            
            return apply_filters( $jckFundraisers->slug.'_total_raised', $totalRaisedHtml, $totalRaised, $goalAmount );
       	}
    
    /**	=============================
        *
        * Returns days remaining
        *
        * @access public
        * @return string
        *
        ============================= */
        
        public function get_days_remaining()
        {
            global $jckFundraisers;
            
            $fundData = $this->get_fund_data();
            
            $daylen = 60*60*24;
            
            $now = date('Y-m-d');
            $target = $fundData['goal']['end'];
            
            $daysRemaining = (strtotime($target)-strtotime($now))/$daylen;
            
            return $daysRemaining;
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
            
            $daysRemaining = $this->get_days_remaining();
            $daysRemaining = ($daysRemaining > 0) ? $daysRemaining : 0;
            
            $daysRemainingHtml = sprintf( 
                '<p class="'.$jckFundraisers->slug.'-stat '.$jckFundraisers->slug.'-stat--days-remaining"><strong>%s</strong> %s</p>',
                $daysRemaining,
                _n( 'day to go', 'days to go', $daysRemaining, $jckFundraisers->slug )
            );
            
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
           	global $jckFundraisers;

            $fundData = get_post_meta($this->id, $jckFundraisers->slug, true);
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
       	* Returns an array of awards, or false if there are none
    	*
       	* @return array|bool
       	*
       	============================= */
       	
       	public function get_reward($rewardId)
       	{
            $rewards = $this->get_rewards();
            
            if($rewards && !empty($rewards))
            {
                foreach($rewards as $key => $reward)
                {
                    if($reward['unique'] == $rewardId)
                        return $rewards[$key];
                }            
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
            // We will also count each reward ID so we have a value to use for the limits
            
            $totalRaised = 0;
            $rewardsClaimed = array();
            
            if(!empty($orderItemMataClean)):
                foreach($orderItemMataClean as $orderItemId => $orderItemMeta):
                
                    $totalRaised = $totalRaised+$orderItemMeta['_line_total'];
                    
                    // Tot up the rewards claimed
                    if(isset($orderItemMeta['Reward'])) 
                    {
                        $claimedCount = (isset($rewardsClaimed[$orderItemMeta['Reward']])) ? (int)$rewardsClaimed[$orderItemMeta['Reward']] : 0;
                        $rewardsClaimed[$orderItemMeta['Reward']] = $claimedCount+1;
                    }
                    
                endforeach;
            endif;
            
            return array(
                'total_raised' => $totalRaised,
                'total_processed_sales' => count($orderItemMataClean),
                'rewards_claimed' => $rewardsClaimed
            );
    	}
    	
    /**	=============================
        *
        * 
        *
        ============================= */
        
        public function get_rewards_claimed($rewardId)
        {
            if(!$rewardId || $rewardId == "")
                return;
                
            $salesData = $this->get_sales_data();
            
            return (isset($salesData['rewards_claimed'][$rewardId])) ? $salesData['rewards_claimed'][$rewardId] : 0;
        }
    }
endif;