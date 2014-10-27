<div id="fundraiser_goal_product_data" class="panel woocommerce_options_panel">    
    <? 
    $goalData = (isset($fundData['goal'])) ? $fundData['goal'] : false;
    //var_dump($fundData); die;
    
    // Goal Type
    woocommerce_wp_select( 
        array( 
            'id' => $this->slug.'[goal][type]', 
            'label' => __( 'Goal Type', 'woocommerce' ), 
            'desc_tip' => 'true',
            'description' => sprintf( 
                __( 'Choose a download type - this controls the <a href="%s">schema</a>.', 'woocommerce' ), 
                'http://schema.org/' 
            ), 
            'value' => ($goalData ? $goalData['type'] : ''),
            'options' => array(
        		'target_date'       => __( 'Target Date', 'woocommerce' ),
        		'target_goal'       => __( 'Target Goal', 'woocommerce' ),
        		'target_goal_date'  => __( 'Target Goal and Date', 'woocommerce' ),
        		'never_ends'        => __( 'Campaign Never Ends', 'woocommerce' )
            ) 
        )
    );
    
    // End Date
    woocommerce_wp_text_input( 
        array( 
            'id' => $this->slug.'[goal][end]', 
            'label' => __( 'End Date', 'woocommerce' ),
            'placeholder' => 'YYYY-MM-DD',
            'value' => ($goalData ? $goalData['end'] : '')
        )
    );
    
    // Goal Amount
    woocommerce_wp_text_input( 
        array( 
            'id' => $this->slug.'[goal][amount]', 
            'label' => __( 'Goal Amount', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')', 
            'data_type' => 'price',
            'value' => ($goalData ? $goalData['amount'] : '')
        )
    );
	?>
</div>