jQuery(function($){ 
jQuery("#gcapt").val("ok");

jQuery( ".datepick" ).datepicker({
	changeMonth: true,
      	  changeYear: true,
      	  dateFormat: 'dd-mm-yy',
      	  yearRange: '1950:2020',
});
jQuery( ".timepick" ).timepicker({
    showSecond: false,
});

});
jQuery(document).ready(function(){
    jQuery('.register').attr('enctype','multipart/form-data');
});





