<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $iconic_woo_fundraisers;

$slug = $iconic_woo_fundraisers->slug;
$fundData = $product->get_fund_data();
$rewardType = (isset($fundData['rewards']['type'])) ? $fundData['rewards']['type'] : false;

if ( ! $product->is_purchasable() ) return;
?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';
	
	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
	
	$currency_pos = get_option( 'woocommerce_currency_pos' );
?>

<?php if ( $product->is_in_stock() ) : ?>
    
    <a href="#<?php echo $slug; ?>-add-to-cart-form" class="single_add_to_cart_button button alt <?php echo $slug; ?>-donate-btn <?php echo $slug; ?>-donate-btn--primary"><?php echo $product->single_add_to_cart_text(); ?></a>
    
    <div id="<?php echo $slug; ?>-add-to-cart-form" class="<?php echo $slug; ?>-add-to-cart-form mfp-hide">

    	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
    
    	<form class="cart" method="post" enctype='multipart/form-data'>
    	 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
    
    	 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />
    	 	<?php if($rewardType == "rewards") { ?><input type="hidden" class="<?php echo $slug; ?>-reward-feild" name="reward" value="" /><?php } ?>
    	 	
    	 	<h2>Enter your donation amount</h2>
    	 	
    	 	<div class="<?php echo $slug; ?>-donation-field-wrap">
                <span class="<?php echo $slug; ?>-symbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
        	 	<input name='price' class='name_price <?php echo $slug; ?>-donation-field <?php echo $slug; ?>-donation-field--currency-<?php echo $currency_pos; ?>' type='text' />
    	 	</div>
    	 	
    	 	<?php wc_get_template( 'single-product/fundraisers/rewards.php' ); ?>
    
    	 	<button type="submit" class="single_add_to_cart_button button alt <?php echo $slug; ?>-donate-btn  <?php echo $slug; ?>-donate-btn--right"><?php echo $product->single_add_to_cart_text(); ?></button>
    
    		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
    	</form>
    
    	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
	
    </div>

<?php endif; ?>