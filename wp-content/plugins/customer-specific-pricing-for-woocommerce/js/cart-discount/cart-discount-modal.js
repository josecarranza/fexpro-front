jQuery( document ).ready(function() {


    jQuery('.woocommerce').on('click', '#csp-cd-more', function(){
        jQuery('#cd-offers-modal').css('display','block');
    });

    jQuery('.woocommerce').on('click', '.csp-cd-modal-close',  function(){
        jQuery('#cd-offers-modal').css('display','none');
    });

});