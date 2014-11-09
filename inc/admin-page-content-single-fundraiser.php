<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="wrap">
    
    <?
    $post = get_post($_GET['fundraiser']);    
    $product = get_product($post->ID);
    
    if($product->product_type == "fundraiser"):
        
        // Get all sales data for this product
        $salesData = $product->get_sales_data();
        $donations = $salesData['donations'];
        
        if(!empty($donations)):
        ?>
        
            <h2 style="margin-bottom: 20px;">
                <?= sprintf('%s "%s"', __('All Donations for',$this->slug), $post->post_title); ?>
                <a href="<?= admin_url('admin.php?page=jckf-donations'); ?>" class="add-new-h2"><? _e('Back to All Fundraisers', $this->slug); ?></a>
            </h2>
            
            <table class="<?= $this->slug; ?>-donations-table wp-list-table widefat fixed" cellspacing="0">
            
                <thead>
                    <tr>
                        <th class="manage-column column-<?= $this->slug; ?>-orderid" scope="col"><? _e('Order',$this->slug); ?></th>
                        <th class="manage-column column-<?= $this->slug; ?>-donation" scope="col"><? _e('Donation',$this->slug); ?></th>
                        <th class="manage-column column-<?= $this->slug; ?>-reward" scope="col"><? _e('Reward',$this->slug); ?></th>
                        <th class="manage-column column-<?= $this->slug; ?>-status" scope="col"><? _e('Status',$this->slug); ?></th>
                    </tr>
                </thead>
                
                <tbody>
                
                    <?        
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
                        
                        <?
                        $status = str_replace('wc-', '', $donation['_order_status']);
                        ?>
                        
                        <tr>
                	        <td><a href="<?= admin_url('post.php?post='.$donation['_order_id'].'&action=edit'); ?>" target="_blank">#<?= $donation['_order_id']; ?></a></td>
                	        <td><?= wc_price($donation['_line_total']); ?></td>
                	        <td><?= (isset($donation['Reward ID'])) ? $donation['Reward ID'] : ""; ?></td>
                	        <td><?= ucwords($status); ?></td>
                	    </tr>
                    
                    <?
                    endforeach;
                    ?>
                
                </tbody>
            
            </table>
        
        <?
        else:
        ?>
        
        <h2 style="margin-bottom: 20px;">
            <?= sprintf('%s "%s"', __('There are currently no donations for',$this->slug), $post->post_title); ?>
            <a href="<?= admin_url('admin.php?page=jckf-donations'); ?>" class="add-new-h2"><? _e('Back to All Fundraisers', $this->slug); ?></a>
        </h2>
                    
        <?
        endif;
        
    endif;
    ?>
</div>