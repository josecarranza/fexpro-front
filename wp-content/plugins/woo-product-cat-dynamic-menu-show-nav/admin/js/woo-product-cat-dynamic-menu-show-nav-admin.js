jQuery(document).ready(function() {
	
	
	jQuery("#term_meta_cat_show_menu_front").click(function(e) {
	    var ischecked= jQuery(this).is(':checked');
	    if(ischecked){
	    	jQuery(this).val(1);
	    	jQuery(this).attr('checked','checked');
	    	jQuery('.catShowMenuChildRadio').css('display','block');
	    }else{
	    	jQuery(this).val(0);
	    	jQuery(this).removeAttr('checked');
	    	jQuery('.catShowMenuChildRadio').css('display','none');
	    }
	   
	    
	}); 



	
});
