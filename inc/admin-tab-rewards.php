<div id="fundraiser_rewards_product_data" class="panel woocommerce_options_panel">
    
    <?
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
            'desc_tip' => 'true',
            'description' => __( 'D', 'woocommerce' ), 
            'value' => $rewardType,
            'options' => array(
        		'no_rewards'    => __( 'No Rewards', 'woocommerce' ),
        		'rewards'  => __( 'Rewards', 'woocommerce' )
            ) 
        )
    );
    ?>
    
    <style type="text/css">
        
        .<?= $this->slug; ?>-rewards-table {
            max-width: 100%;
            margin: 20px 12px;
            width: auto;
        }
            
            .<?= $this->slug; ?>-rewards-table th {
                padding: 15px 10px;
            }
            
            .<?= $this->slug; ?>-rewards-table td {
                vertical-align: top;
            }
            
            .<?= $this->slug; ?>-text-input--full,
            input[type=email].<?= $this->slug; ?>-text-input--full,
            input[type=text].<?= $this->slug; ?>-text-input--full,
            input[type=number].<?= $this->slug; ?>-text-input--full {
                width: 100%;
            }
            
            .<?= $this->slug; ?>-textarea {
                resize: vertical;
            }
        
            .column-<?= $this->slug; ?>-description {
                width: 200px;
            }
            
            .column-<?= $this->slug; ?>-actions {
                width: 50px !important;
            }
                
                .column-<?= $this->slug; ?>-actions__add,
                .column-<?= $this->slug; ?>-actions__remove {
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
                    
                    .column-<?= $this->slug; ?>-actions__add:hover,
                    .column-<?= $this->slug; ?>-actions__remove:hover {
                        border-color: #2ea2cc;
                    }
                
                .column-<?= $this->slug; ?>-actions__remove {
                    margin-right: 0;
                    line-height: 8px;
                }
        
    </style>
    
    <table class="<?= $this->slug; ?>-rewards-table widefat form-table" cellspacing="0">
        <thead>
            <tr>
                <th class="manage-column column-<?= $this->slug; ?>-unique" scope="col"><? _e('Unique Code',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-amount" scope="col"><? _e('Donation Amount',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-description" scope="col"><? _e('Description',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-limit" scope="col"><? _e('Reward Limit',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-delivery" scope="col"><? _e('Estimated Delivery Date',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-actions" scope="col">&nbsp;</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="manage-column column-<?= $this->slug; ?>-unique" scope="col"><? _e('Unique Code',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-amount" scope="col"><? _e('Donation Amount',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-description" scope="col"><? _e('Description',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-limit" scope="col"><? _e('Reward Limit',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-delivery" scope="col"><? _e('Estimated Delivery Date',$this->slug); ?></th>
                <th class="manage-column column-<?= $this->slug; ?>-actions" scope="col">&nbsp;</th>
            </tr>
        </tfoot>
        <tbody>
            <? if(!empty($rewards)): ?>
                <? $i = 0; foreach($rewards as $reward): ?>
                
                    <tr class="<?= ($i % 2 == 0 ? 'alternate' : ''); ?>">
                        <td class="column-<?= $this->slug; ?>-unique">
                            <input type="text" class="<?= $this->slug; ?>-text-input <?= $this->slug; ?>-text-input--full" name="<?= $this->slug; ?>[rewards][rewards][<?= $i; ?>][unique]" value="<?= $reward['unique']; ?>">
                        </td>
                        <td class="column-<?= $this->slug; ?>-amount">
                            <input type="number" class="<?= $this->slug; ?>-text-input <?= $this->slug; ?>-text-input--full" name="<?= $this->slug; ?>[rewards][rewards][<?= $i; ?>][amount]" value="<?= $reward['amount']; ?>">
                        </td>
                        <td class="column-<?= $this->slug; ?>-description">
                            <textarea class="<?= $this->slug; ?>-textarea" name="<?= $this->slug; ?>[rewards][rewards][<?= $i; ?>][description]"><?= $reward['description']; ?></textarea>
                        </td>
                        <td class="column-<?= $this->slug; ?>-limit">
                            <input type="text" class="<?= $this->slug; ?>-text-input <?= $this->slug; ?>-text-input--full" name="<?= $this->slug; ?>[rewards][rewards][<?= $i; ?>][limit]" value="<?= $reward['limit']; ?>">
                        </td>
                        <td class="column-<?= $this->slug; ?>-delivery">
                            <input type="text" class="<?= $this->slug; ?>-text-input <?= $this->slug; ?>-text-input--full" name="<?= $this->slug; ?>[rewards][rewards][<?= $i; ?>][delivery]" placeholder="YYYY-MM-DD" value="<?= $reward['delivery']; ?>">
                        </td>
                        <td class="column-<?= $this->slug; ?>-actions">
                            <a href="#" class="column-<?= $this->slug; ?>-actions__add jckAddRow">+</a>
                            <a href="#" class="column-<?= $this->slug; ?>-actions__remove jckRmRow">-</a>
                        </td>
                    </tr>
                    
                <? $i++; endforeach; ?>            
            <? endif; ?>
        </tbody>
    </table>
    
    <?
    echo 'Need page to show donations, and allow filtering by reward, if applicable<br>';
    echo 'Page will list: Name/Amount/Fundraiser/applicable Reward/Date<br><br>';
    echo 'Shortcodes/widgets for: display donators and message/Fundraiser progress<br>';
    echo 'Paypal option, authorise upon close of fundraiser.';
    ?>
</div>