<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
$args = array(
	'post_type' => 'product',
	'posts_per_page' => -1,
	'product_type' => 'fundraiser'
);

$fundraisers = new WP_Query( $args );
?>

<div class="wrap">
    
    <h2 style="margin-bottom: 20px;"><?php _e('All Fundraisers',$this->slug); ?></h2>

    <?php if ( $fundraisers->have_posts() ): ?>
    
        <table class="<?php echo $this->slug; ?>-fundraisers-table wp-list-table widefat fixed" cellspacing="0">
            
            <thead>
                <tr>
                    <th class="manage-column column-<?php echo $this->slug; ?>-name" scope="col"><?php _e('Event Name',$this->slug); ?></th>
                    <th class="manage-column column-<?php echo $this->slug; ?>-raised" scope="col"><?php _e('Total Raised',$this->slug); ?></th>
                    <th class="manage-column column-<?php echo $this->slug; ?>-status" scope="col"><?php _e('Status',$this->slug); ?></th>
                </tr>
            </thead>
            
            <tbody>
    	
            	<?php while ( $fundraisers->have_posts() ): $fundraisers->the_post(); ?>
            	    <?php 
                    $product = get_product(get_the_id());
                    $salesData = $product->get_sales_data();
                    $totalRaised = wc_price($salesData['total_raised']);
                    $status = ($product->is_purchasable()) ? __('Active', $this->slug) : __('Ended', $this->slug);
                    ?>
            	    <tr>
            	        <td><a href="<?php echo admin_url('admin.php?page='.$this->slug.'-donations&fundraiser='.get_the_id()); ?>"><?php the_title(); ?></a></td>
            	        <td><?php echo $totalRaised; ?></td>
            	        <td><?php echo $status; ?></td>
            	    </tr>
            	<?php endwhile; ?>
        	
            </tbody>
    	
        </table>
    	
    <?php endif; wp_reset_postdata(); ?>

</div>