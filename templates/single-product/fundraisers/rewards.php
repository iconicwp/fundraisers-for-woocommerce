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
        
<? if($rewards && !empty($rewards)): ?>
    <h2>Select your reward</h2>
    
    <ul class="<?= $slug; ?>-rewards">
        <? foreach($rewards as $reward): ?>
            <li class="jckf-reward">
                <a class="<?= $slug; ?>-reward__link <?= $slug; ?>-reward__link--donate" href="#<?= $slug; ?>-add-to-cart-form" data-amount="<?= $reward['amount']+5; ?>" data-reward-id="<?= $reward['unique']; ?>">
                    <span class="<?= $slug; ?>-reward__select"><span class="<?= $slug; ?>-reward__select-text">Select this Reward</span></span>
                    
                    <? if(isset($reward['limit']) && $reward['limit'] != "") { ?>
                        <div class="<?= $slug; ?>-reward__limit"><?= $reward['limit']; ?> left of <?= $reward['limit']; ?></div>
                    <? } ?>
                    <? if(isset($reward['amount']) && $reward['amount'] != "") { ?>
                        <div class="<?= $slug; ?>-reward__donate">Donate <?= wc_price($reward['amount']); ?> or more</div>
                    <? } ?>
                    <? if(isset($reward['description']) && $reward['description'] != "") { ?>
                        <div class="<?= $slug; ?>-reward__description"><?= $reward['description']; ?></div>
                    <? } ?>
                    <? if(isset($reward['delivery']) && $reward['delivery'] != "") { ?>
                        <div class="<?= $slug; ?>-reward__delivery"><strong>Estimated Delivery:</strong> <?= $reward['delivery']; ?></div>
                    <? } ?>
                </a>
            </li>
        <? endforeach; ?>
    </ul>
<? endif; ?>