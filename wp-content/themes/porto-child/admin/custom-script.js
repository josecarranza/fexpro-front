jQuery(document).ready(function($) {
	jQuery(".edit-order-item").on('click', function() {
		//jQuery(".split-input input").attr('readonly','readonly');
		jQuery(this).next().hide();
	});
//jQuery(".edit-order-item").on('click', function() {
	
	//alert(box_unit);
	//alert(unit_price);
	jQuery( "input.quantity" ).on('keyup change keypress click', function() {
		var box_unit = jQuery( this ).closest( 'tr.item' ).children(".item_box_qty").children().data('custom_box');
		var unit_price = jQuery( this ).closest( 'tr.item' ).children(".item_box_qty").children().data('custom_price');
	
		var getCal = jQuery(this).val() * unit_price * box_unit;
		//alert(getCal);
		//jQuery( this ).trigger( 'quantity_changed' );
		jQuery( this ).closest( 'tr.item' ).children(".line_cost").children(".edit").children(".split-input").children(".input").children("input").val(getCal);
		
	});
	
//});

	// jQuery('.par_cat:not(.active)').on('click', function(){		 
	// 	jQuery(this).find('ul').slideToggle('slow');
	// 	jQuery(this).toggleClass('active');
	// 	jQuery(this).siblings().find('ul').slideUp('slow');
	// 	jQuery(this).siblings().removeClass('active');
	// });
});

jQuery(document).ajaxComplete(function($) {
	jQuery(".edit-order-item").on('click', function() {
		//jQuery(".split-input input").attr('readonly','readonly');
		jQuery(this).next().hide();
	});
	//jQuery(".edit-order-item").on('click', function() {
	 
	
	//alert(box_unit);
	//alert(unit_price);
	jQuery( "input.quantity" ).on('keyup change keypress click', function() {
		var box_unit = jQuery( this ).closest( 'tr.item' ).children(".item_box_qty").children().data('custom_box');
		var unit_price = jQuery( this ).closest( 'tr.item' ).children(".item_box_qty").children().data('custom_price');
		var getCal = jQuery(this).val() * unit_price * box_unit;
		//alert(getCal);
		//jQuery( this ).trigger( 'quantity_changed' );
		jQuery( this ).closest( 'tr.item' ).children(".line_cost").children(".edit").children(".split-input").children(".input").children("input").val(getCal);
		
	});
	
	//});
	
	jQuery(".admin-bar.post-type-shop_order .wc-backbone-modal-main footer .inner button#btn-ok").on('click', function(){
		jQuery("#woocommerce-order-items .woocommerce_order_items_wrapper table.woocommerce_order_items .line_cost").hide();
		jQuery.ajax({
		beforeSend: function() {jQuery("#woocommerce-order-items .woocommerce_order_items_wrapper table.woocommerce_order_items .line_cost").hide();},
        complete: function(){
			    jQuery("#woocommerce-order-items .woocommerce_order_items_wrapper table.woocommerce_order_items .line_cost").hide();
				location.reload();
			}
		});
	});
});