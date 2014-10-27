<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Fundraiser Shortcodes Class
 *
 * @class 		JCKF_Shortcodes
 * @version		1.0.0
 * @category	Class
 * @author 		James Kemp
 */
 
class JCKF_Shortcodes {

/**	=============================
   	*
   	* Init shortcodes
	*
   	* @access public
   	*
   	============================= */

	public static function init() {
		// Define shortcodes
		$shortcodes = array(
			'fundstat' => __CLASS__ . '::fundstat'
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

/**	=============================
   	*
   	* Fundstat shotcode
   	*
   	* Shows data like number of donations, donation goal, time left
	*
	* @param array $atts (default: array())
   	* @access public
   	*
   	============================= */
    
    public  static function fundstat( $atts )
    {
        global $post, $product, $jckFundraisers;
        
        $productId = $product->id;
        $fundData = $product->get_fund_data();
        $salesData = $product->get_sales_data();
        
        $a = shortcode_atts( array(
            'type' => false
        ), $atts );
        
        $return = "";
        
        if($product):
            
            if($a['type'] == "donators"):
                
                $return .= $product->get_total_donations_html();
                
            elseif($a['type'] == "donations"):
                
                $return .= $product->get_total_raised_html();
                
            elseif($a['type'] == "timeleft"):
                
                $return .= $product->get_days_remaining_html();
            
            endif;
        
        endif;
    
        return $return;
    }

}