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
    	}, '.jckf-reward__link:not(.jckf-reward-unavailable)');
	}	
	
	function setupRewardLinks()
	{
	    var $rewardLinks = $('.jckf-reward__link').not('.jckf-reward-unavailable'),
	        $donationField = $('.jckf-donation-field'),
	        $rewardField = $('.jckf-reward-feild');
	    
        $rewardLinks.on('click', function(){
            var amount = parseInt($(this).attr('data-amount')),
                currAmount = ($donationField.val() != "") ? parseInt($donationField.val()) : 0,
                uniqueId = $(this).attr('data-reward-id'),
                $selected = $('.jckf-reward__link.selected');
            
            // Update donation field
            
            $donationField.val(amount);
            
            // Update reward ID field
            
            $rewardField.val(uniqueId);
            
            // Add selected class and flag
            
            if($selected.length > 0)
            {
                $selected.each(function(){
                   $(this).removeClass('selected').find('.jckf-reward__selected').remove(); 
                });
            }
            
            $('[data-reward-id="'+uniqueId+'"]').addClass('selected').prepend($('<div class="jckf-reward-flag jckf-reward__selected">Selected</div>'));
        });	
	}
	
}(jQuery, document));