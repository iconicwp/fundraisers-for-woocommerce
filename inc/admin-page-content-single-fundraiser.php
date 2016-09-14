<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="wrap">
    
    <?php
    $post = get_post($_GET['fundraiser']);    
    $product = get_product($post->ID);
    
    if($product->product_type == "fundraiser"):
        
        // Get all sales data for this product
        $salesData = $product->get_sales_data();
        $donations = $salesData['donations'];
        
        if(!empty($donations)):
        ?>
        
            <h2 style="margin-bottom: 20px;">
                <?php echo sprintf('%s "%s"', __('All Donations for',$this->slug), $post->post_title); ?>
                <a href="<?php echo admin_url('admin.php?page=iconic-woo-fundraisers-donations'); ?>" class="add-new-h2"><?php _e('Back to All Fundraisers', $this->slug); ?></a>
            </h2>
            
            <table class="<?php echo $this->slug; ?>-donations-table wp-list-table widefat fixed" cellspacing="0">
            
                <thead>
                    <tr>
                        <th class="manage-column column-<?php echo $this->slug; ?>-orderid" scope="col"><?php _e('Order',$this->slug); ?></th>
                        <th class="manage-column column-<?php echo $this->slug; ?>-donation" scope="col"><?php _e('Donation',$this->slug); ?></th>
                        <th class="manage-column column-<?php echo $this->slug; ?>-reward" scope="col"><?php _e('Reward',$this->slug); ?></th>
                        <th class="manage-column column-<?php echo $this->slug; ?>-status" scope="col"><?php _e('Status',$this->slug); ?></th>
                    </tr>
                </thead>
                
                <tbody>
                
                    <?php        
                    // loop the donations and add them to a rewards array,
                    // then use this data for sorting the array by reward id
                    $rewards = array();
                    foreach ($donations as $key => $row)
                    {
                        $rewards[$key] = (isset($row['Reward ID'])) ? $row['Reward ID'] : "";
                    }
                    array_multisort($rewards, SORT_DESC, $donations);
                    
                    // loop the donations and output in a table
                    foreach($donations as $donation):
                    ?>
                        
                        <?php
                        $status = str_replace('wc-', '', $donation['_order_status']);
                        ?>
                        
                        <tr>
                	        <td><a href="<?php echo admin_url('post.php?post='.$donation['_order_id'].'&action=edit'); ?>" target="_blank">#<?php echo $donation['_order_id']; ?></a></td>
                	        <td><?php echo wc_price($donation['_line_total']); ?></td>
                	        <td><?php echo (isset($donation['Reward ID'])) ? $donation['Reward ID'] : ""; ?></td>
                	        <td><?php echo ucwords($status); ?></td>
                	    </tr>
                    
                    <?php
                    endforeach;
                    ?>
                
                </tbody>
            
            </table>
        
        <?php
        else:
        ?>
        
        <h2 style="margin-bottom: 20px;">
            <?php echo sprintf('%s "%s"', __('There are currently no donations for',$this->slug), $post->post_title); ?>
            <a href="<?php echo admin_url('admin.php?page=iconic-woo-fundraisers-donations'); ?>" class="add-new-h2"><?php _e('Back to All Fundraisers', $this->slug); ?></a>
        </h2>
                    
        <?php
        endif;
        
    endif;
    ?>
</div>