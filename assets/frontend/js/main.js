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
            delegate: '.iconic-woo-fundraisers-donate-btn--primary, .iconic-woo-fundraisers-reward__link--donate', // child items selector, by clicking on it popup will open
            type: 'inline'
        });
	}

	function setupSelectOverlay()
	{
    	$('body').on({
        	mouseenter: function()
        	{
            	$(this).addClass('iconic-woo-fundraisers-reward__link--hover');
        	},
        	mouseleave: function()
        	{
            	$(this).removeClass('iconic-woo-fundraisers-reward__link--hover');
        	}
    	}, '.iconic-woo-fundraisers-reward__link:not(.iconic-woo-fundraisers-reward-unavailable)');
	}

	function setupRewardLinks()
	{
	    var $rewardLinks = $('.iconic-woo-fundraisers-reward__link').not('.iconic-woo-fundraisers-reward-unavailable'),
	        $donationField = $('.iconic-woo-fundraisers-donation-field'),
	        $rewardField = $('.iconic-woo-fundraisers-reward-feild');

        $rewardLinks.on('click', function(){
            var amount = parseInt($(this).attr('data-amount')),
                currAmount = ($donationField.val() != "") ? parseInt($donationField.val()) : 0,
                uniqueId = $(this).attr('data-reward-id'),
                $selected = $('.iconic-woo-fundraisers-reward__link.selected');

            // Update donation field

            $donationField.val(amount);

            // Update reward ID field

            $rewardField.val(uniqueId);

            // Add selected class and flag

            if($selected.length > 0)
            {
                $selected.each(function(){
                   $(this).removeClass('selected').find('.iconic-woo-fundraisers-reward__selected').remove();
                });
            }

            $('[data-reward-id="'+uniqueId+'"]').addClass('selected').prepend($('<div class="iconic-woo-fundraisers-reward-flag iconic-woo-fundraisers-reward__selected">Selected</div>'));
        });
	}

}(jQuery, document));