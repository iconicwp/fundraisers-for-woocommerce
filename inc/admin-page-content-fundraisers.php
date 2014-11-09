<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?
$args = array(
	'post_type' => 'product',
	'posts_per_page' => -1,
	'product_type' => 'fundraiser'
);

$fundraisers = new WP_Query( $args );
?>

<div class="wrap">
    
    <h2 style="margin-bottom: 20px;"><? _e('All Fundraisers',$this->slug); ?></h2>

    <? if ( $fundraisers->have_posts() ): ?>
    
        <table class="<?= $this->slug; ?>-fundraisers-table wp-list-table widefat fixed" cellspacing="0">
            
            <thead>
                <tr>
                    <th class="manage-column column-<?= $this->slug; ?>-name" scope="col"><? _e('Event Name',$this->slug); ?></th>
                    <th class="manage-column column-<?= $this->slug; ?>-raised" scope="col"><? _e('Total Raised',$this->slug); ?></th>
                    <th class="manage-column column-<?= $this->slug; ?>-status" scope="col"><? _e('Status',$this->slug); ?></th>
                </tr>
            </thead>
            
            <tbody>
    	
            	<? while ( $fundraisers->have_posts() ): $fundraisers->the_post(); ?>
            	    <? 
                    $product = get_product(get_the_id());
                    $salesData = $product->get_sales_data();
                    $totalRaised = wc_price($salesData['total_raised']);
                    $status = ($product->is_purchasable()) ? __('Active', $this->slug) : __('Ended', $this->slug);
                    ?>
            	    <tr>
            	        <td><a href="<?= admin_url('admin.php?page='.$this->slug.'-donations&fundraiser='.get_the_id()); ?>"><? the_title(); ?></a></td>
            	        <td><?= $totalRaised; ?></td>
            	        <td><?= $status; ?></td>
            	    </tr>
            	<? endwhile; ?>
        	
            </tbody>
    	
        </table>
    	
    <? endif; wp_reset_postdata(); ?>

</div>