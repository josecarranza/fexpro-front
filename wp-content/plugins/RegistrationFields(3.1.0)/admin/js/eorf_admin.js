jQuery(document).ready(function(){
    jQuery( ".datepick" ).datepicker();
    jQuery( ".timepick" ).timepicker({
        showSecond: false,
    });

});

jQuery(document).ready(function(){
    jQuery('#your-profile').attr('enctype','multipart/form-data');
});

function Check_all_checkbox() {
    "use strict";

    var checkbox = document.getElementById('checkAll');
    if (checkbox.checked == true) {
        jQuery('.checkboxes input:checkbox').prop('checked', true);
    } else {

        jQuery('.checkboxes input:checkbox').prop('checked', false);
    }
}

