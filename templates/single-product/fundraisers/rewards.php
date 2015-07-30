<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $jckFundraisers;

$rewards = $product->get_rewards();
$slug = $jckFundraisers->slug;
?>
        
<?php if($rewards && !empty($rewards)): ?>
    <h2>Select your reward</h2>
    
    <ul class="<?php echo $slug; ?>-rewards">
        <?php foreach($rewards as $reward): ?>
            
            <?php
            $rewardsClaimed = $product->get_rewards_claimed($reward['unique']);
            $remaining = (isset($reward['limit']) && $reward['limit'] != "") ? $reward['limit']-$rewardsClaimed : -1;
            ?>
            
            <li class="jckf-reward">
                <a class="<?php echo $slug; ?>-reward__link <?php echo $slug; ?>-reward__link--donate <?php if($remaining == 0) echo $slug.'-reward-unavailable'; ?>" href="#<?php echo $slug; ?>-add-to-cart-form" data-amount="<?php echo $reward['amount']; ?>" data-reward-id="<?php echo $reward['unique']; ?>">
                    <span class="<?php echo $slug; ?>-reward__select"><span class="<?php echo $slug; ?>-reward__select-text">Select this Reward</span></span>
                    
                    <?php if(isset($reward['limit']) && $reward['limit'] != "") { ?>
                        <div class="<?php echo $slug; ?>-reward-flag <?php echo $slug; ?>-reward__limit"><?php echo $remaining; ?> left of <?php echo $reward['limit']; ?></div>
                    <?php } ?>
                    <?php if(isset($reward['amount']) && $reward['amount'] != "") { ?>
                        <div class="<?php echo $slug; ?>-reward__donate">Donate <?php echo wc_price($reward['amount']); ?> or more</div>
                    <?php } ?>
                    <?php if(isset($reward['description']) && $reward['description'] != "") { ?>
                        <div class="<?php echo $slug; ?>-reward__description"><?php echo $reward['description']; ?></div>
                    <?php } ?>
                    <?php if(isset($reward['delivery']) && $reward['delivery'] != "") { ?>
                        <div class="<?php echo $slug; ?>-reward__delivery"><strong>Estimated Delivery:</strong> <?php echo $reward['delivery']; ?></div>
                    <?php } ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>