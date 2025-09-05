// jQuery(document).ready(function () {
//     //toggle the component with class accordion_body
//     jQuery(".brandTitle").click(function () {       
//         if (jQuery(this).next(".table").is(':visible')) {
//             jQuery(this).next(".table").slideUp(100);
            
//         } else {
//             jQuery(this).next(".table").slideDown(100);            
//         }
//     });
// });

jQuery(document).ready(function () {

jQuery('.brandTitle').click( function() {
        jQuery(this).toggleClass("active");
        jQuery(this).siblings().delay(300).slideToggle('slow');  
    });
});