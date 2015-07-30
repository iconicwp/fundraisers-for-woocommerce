<div id="fundraiser_rewards_product_data" class="panel woocommerce_options_panel">
    
    <?php
    $rewardsData = (isset($fundData['rewards'])) ? $fundData['rewards'] : false;
    $rewardType = ($rewardsData && isset($rewardsData['type'])) ? $rewardsData['type'] : "no_rewards";
    $rewards = ($rewardsData && isset($rewardsData['rewards'])) ? $rewardsData['rewards'] : array(
        array(
            'amount' => '',
            'description' => '',
            'limit' => '',
            'delivery' => ''
        )
    );
    
    // Fundraiser Type
    woocommerce_wp_select( 
        array( 
            'id' => $this->slug.'[rewards][type]', 
            'label' => __( 'Rewards Type', 'woocommerce' ),
            'value' => $rewardType,
            'options' => array(
        		'no_rewards'    => __( 'No Rewards', 'woocommerce' ),
        		'rewards'  => __( 'Rewards', 'woocommerce' )
            ) 
        )
    );
    ?>
    
    <style type="text/css">
        
        .<?php echo $this->slug; ?>-rewards-table {
            max-width: 100%;
            margin: 20px 12px !important;
            width: auto;
        }
            
            .<?php echo $this->slug; ?>-rewards-table th {
                padding: 15px 10px;
            }
            
            .<?php echo $this->slug; ?>-rewards-table td {
                vertical-align: top;
            }
            
            .<?php echo $this->slug; ?>-text-input--full,
            input[type=email].<?php echo $this->slug; ?>-text-input--full,
            input[type=text].<?php echo $this->slug; ?>-text-input--full,
            input[type=number].<?php echo $this->slug; ?>-text-input--full {
                width: 100%;
            }
            
            .<?php echo $this->slug; ?>-textarea {
                resize: vertical;
            }
        
            .column-<?php echo $this->slug; ?>-description {
                width: 200px;
            }
            
            .column-<?php echo $this->slug; ?>-actions {
                width: 50px !important;
            }
                
                .column-<?php echo $this->slug; ?>-actions__add,
                .column-<?php echo $this->slug; ?>-actions__remove {
                    display: inline-block;
                    width: 10px;
                    height: 10px;
                    background: #fff;
                    color: #a8a8a8;
                    text-align: center;
                    line-height: 10px;
                    border-radius: 10px;
                    border: 2px solid #a8a8a8;
                    float: left;
                    margin: 6px 4px 0 0;
                }
                    
                    .column-<?php echo $this->slug; ?>-actions__add:hover,
                    .column-<?php echo $this->slug; ?>-actions__remove:hover {
                        border-color: #2ea2cc;
                    }
                
                .column-<?php echo $this->slug; ?>-actions__remove {
                    margin-right: 0;
                    line-height: 8px;
                }
        
    </style>
    
    <table class="<?php echo $this->slug; ?>-rewards-table widefat form-table" cellspacing="0">
        <thead>
            <tr>
                <th class="manage-column column-<?php echo $this->slug; ?>-unique" scope="col"><?php _e('Unique Code',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-amount" scope="col"><?php _e('Donation Amount',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-description" scope="col"><?php _e('Description',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-limit" scope="col"><?php _e('Reward Limit',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-delivery" scope="col"><?php _e('Estimated Delivery Date',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-actions" scope="col">&nbsp;</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="manage-column column-<?php echo $this->slug; ?>-unique" scope="col"><?php _e('Unique Code',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-amount" scope="col"><?php _e('Donation Amount',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-description" scope="col"><?php _e('Description',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-limit" scope="col"><?php _e('Reward Limit',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-delivery" scope="col"><?php _e('Estimated Delivery Date',$this->slug); ?></th>
                <th class="manage-column column-<?php echo $this->slug; ?>-actions" scope="col">&nbsp;</th>
            </tr>
        </tfoot>
        <tbody>
            <?php if(!empty($rewards)): ?>
                <?php $i = 0; foreach($rewards as $reward): ?>
                
                    <tr class="<?php echo ($i % 2 == 0 ? 'alternate' : ''); ?>">
                        <td class="column-<?php echo $this->slug; ?>-unique">
                            <input type="text" class="<?php echo $this->slug; ?>-text-input <?php echo $this->slug; ?>-text-input--full" name="<?php echo $this->slug; ?>[rewards][rewards][<?php echo $i; ?>][unique]" value="<?php if(isset($reward['unique'])) echo $reward['unique']; ?>">
                        </td>
                        <td class="column-<?php echo $this->slug; ?>-amount">
                            <input type="number" class="<?php echo $this->slug; ?>-text-input <?php echo $this->slug; ?>-text-input--full" name="<?php echo $this->slug; ?>[rewards][rewards][<?php echo $i; ?>][amount]" value="<?php echo $reward['amount']; ?>">
                        </td>
                        <td class="column-<?php echo $this->slug; ?>-description">
                            <textarea class="<?php echo $this->slug; ?>-textarea" name="<?php echo $this->slug; ?>[rewards][rewards][<?php echo $i; ?>][description]"><?php echo $reward['description']; ?></textarea>
                        </td>
                        <td class="column-<?php echo $this->slug; ?>-limit">
                            <input type="number" class="<?php echo $this->slug; ?>-text-input <?php echo $this->slug; ?>-text-input--full" name="<?php echo $this->slug; ?>[rewards][rewards][<?php echo $i; ?>][limit]" value="<?php echo $reward['limit']; ?>">
                        </td>
                        <td class="column-<?php echo $this->slug; ?>-delivery">
                            <input type="text" class="<?php echo $this->slug; ?>-text-input <?php echo $this->slug; ?>-text-input--full" name="<?php echo $this->slug; ?>[rewards][rewards][<?php echo $i; ?>][delivery]" placeholder="YYYY-MM-DD" value="<?php echo $reward['delivery']; ?>">
                        </td>
                        <td class="column-<?php echo $this->slug; ?>-actions">
                            <a href="#" class="column-<?php echo $this->slug; ?>-actions__add jckAddRow">+</a>
                            <a href="#" class="column-<?php echo $this->slug; ?>-actions__remove jckRmRow">-</a>
                        </td>
                    </tr>
                    
                <?php $i++; endforeach; ?>            
            <?php endif; ?>
        </tbody>
    </table>
    
</div>