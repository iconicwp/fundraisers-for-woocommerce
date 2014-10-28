(function($, document)
{
    $(document).ready(function(){
        setupFundraiserDonateBtn();
        setupSelectOverlay();
        setupRewardLinks();
    });
    
	function setupFundraiserDonateBtn()
	{
    	$('body').magnificPopup({
            delegate: '.jckf-donate-btn--primary, .jckf-reward__link--donate', // child items selector, by clicking on it popup will open
            type: 'inline'
        });
	}
	
	function setupSelectOverlay()
	{
    	$('body').on({
        	mouseenter: function()
        	{
            	$(this).addClass('jckf-reward__link--hover');
        	},
        	mouseleave: function()
        	{
            	$(this).removeClass('jckf-reward__link--hover');
        	}
    	}, '.jckf-reward__link');
	}	
	
	function setupRewardLinks()
	{
	    var $rewardLinks = $('.jckf-reward__link'),
	        $donationField = $('.jckf-donation-field'),
	        $rewardField = $('.jckf-reward-feild');
	    
        $rewardLinks.on('click', function(){
            var amount = parseInt($(this).attr('data-amount')),
                currAmount = ($donationField.val() != "") ? parseInt($donationField.val()) : 0,
                uniqueId = $(this).attr('data-reward-id');
            
            if(amount >= currAmount)
            {
                $donationField.val(amount);
            }
            
            $rewardField.val(uniqueId);
            
            // @todo Add selected class
        });	
	}
	
}(jQuery, document));