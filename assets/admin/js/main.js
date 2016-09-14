(function($, document)
{
	/* on doc ready */
	$(document).ready(function()
	{
		jckManageRows();
	});

	/* functions */

	function jckManageRows()
	{
    	/*  === Add Row ===  */

    	jQuery('table').on('click', '.jckAddRow', function(){
    		var group = jQuery(this).closest('table tbody'),
    		    row = jQuery(this).closest('tr'),
    		    clone = row.clone();
    		clone.find("script,noscript,style").remove().end()
    		.find('input[type=text], input[type=number]').removeClass('hasTimepicker hasDatepicker').val('').end()
    		.find('input[type=checkbox], input[type=radio]').attr('checked',false).end()
    		.find(".hide").removeClass('hide');
    		row.find(".hide").removeClass('hide');
    		row.after(clone);
    		jckReindexRepeaters(group);
    		return false;
    	});

    	/*  === Remove Row ===  */

    	jQuery('table').on('click', '.jckRmRow', function(){
    		var group = jQuery(this).closest('table tbody'),
    		    row = jQuery(this).closest('tr');
    		row.remove();
    		jckReindexRepeaters(group);
    		return false;
    	});
	}

	/*  === Helper Functions ===  */

    function jckReindexRepeaters(group){

    	if(group.find("tr").length == 1) {
    		group.find(".jckRmRow").hide();
    	} else {
    		group.find(".jckRmRow").show();
    	}

    	group.find("tr").each(function(index) {
    		jQuery(this).removeClass('alternate');
    		if(index%2 == 0) jQuery(this).addClass('alternate');
            jQuery(this).find("input, textarea").each(function() {
            	var name = jQuery(this).attr('name');
            	var id = jQuery(this).attr('id');

    			if(typeof name !== typeof undefined && name !== false) jQuery(this).attr('name', name.replace(/\[\d+\]/, '['+index+']'));
    			if(typeof id !== typeof undefined && id !== false) jQuery(this).attr('id', id.replace(/\-id\d+\-/, '-id'+index+'-'));
            });
        });
    }

}(jQuery, document));