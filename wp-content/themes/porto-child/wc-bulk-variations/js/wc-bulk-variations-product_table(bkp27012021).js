jQuery( document ).ready(function( $ ) {
	
	// Add quickview compatibility
	$( document.body ).on( 'quick_view_pro:load', function() {
		
		wcbvp_calculate_product_totals()
		
		jQuery( '.wcbvp_quantity' ).on('input', function() {
			
			var individual    = jQuery( this ).data( 'individual' );
			var table_id      = jQuery( this ).data( 'table_id' );
			var current_value = jQuery( this ).val();
			
			if ( individual && current_value > 0 ) {
				jQuery( '.wcbvp_quantity[data-table_id="' + table_id + '"]' ).not( this ).val( 0 );
				if ( current_value > 1 ) {
					jQuery( this ).val( 1 );
				}
			}
				
			wcbvp_calculate_product_totals();
		} );
	} );		
	
	jQuery( '.wcbvp_quantity' ).on('input', function() {
		
		var individual    = jQuery( this ).data( 'individual' );
		var table_id      = jQuery( this ).data( 'table_id' );
		var current_value = jQuery( this ).val();
		
		if ( individual && current_value > 0 ) {
			jQuery( '.wcbvp_quantity[data-table_id="' + table_id + '"]' ).not( this ).val( 0 );
			if ( current_value > 1 ) {
				jQuery( this ).val( 1 );
			}
		}
				
		wcbvp_calculate_product_totals();
	} );
	
	wcbvp_calculate_product_totals()
	
	jQuery( '.wcbvp_quantity' ).on( 'keydown keyup', function( e ) {
			
			var max = jQuery( this ).attr( 'max' ).toString();
			
			
			if ( jQuery.isNumeric( max ) ) {
				
				max = parseInt( max );
			    if ( jQuery( this ).val() > max && e.keyCode !== 46 && e.keyCode !== 8  ) {
			       e.preventDefault();
			       jQuery( this ).val(max);
			       wcbvp_calculate_product_totals();
			    }
		    }
		} );
	
	function wcbvp_calculate_product_totals() {
		
		var total_quantity = new Array();
		var total_price    = new Array();
		var product_list   = [];
						        
		jQuery( ".wcbvp_quantity" ).each(function( i ) {
			
			var table_id      = jQuery( this ).data( 'table_id' );
			var quantity      = parseInt( jQuery( this ).val() );
			var price         = parseFloat( jQuery( this ).data( 'price' ) );
			var product_id    = parseInt( jQuery( this ).data( 'product_id' ) );
						
			if ( quantity >= 0 ) {

				var product_price = quantity * price
				var old_qty   = 0;
				var old_price = 0;
				
				if ( total_quantity[ table_id ] !== undefined ) {
					old_qty += total_quantity[ table_id ];
				}
				
				if ( total_price[ table_id ] !== undefined ) {
					old_price += total_price[ table_id ];
				}
				
				total_quantity[ table_id ]   = old_qty + quantity;
				total_price[ table_id ]      = old_price + product_price;
				
				for ( i = 0; i < quantity; i++ ) {
					if ( product_list[table_id] == undefined ) {
						product_list[table_id] = new Array();
					} 
					product_list[table_id].push( product_id );
				}
			}
		} );
		
		function b2_format_price( nStr, d_separator, t_separator ) {
			
		    nStr += '';
		    x = nStr.split('.');
		    x1 = x[0];
		    x2 = x.length > 1 ? d_separator + x[1] : '';
		    var rgx = /(\d+)(\d{3})/;
		    while (rgx.test(x1)) {
		            x1 = x1.replace(rgx, '$1' + t_separator + '$2');
		    }
		    return x1 + x2;
		}
		
		jQuery( ".wcbvp_total_price" ).each(function( i ) {
			
			var decimals    = b2_currency_options.decimals;
			var d_separator = b2_currency_options.d_separator;
			var t_separator = b2_currency_options.t_separator;
			
			if ( decimals == undefined ) {
				decimals = 2;
			}
			if ( d_separator == undefined ) {
				d_separator = '.';
			}
			if ( t_separator == undefined ) {
				t_separator = ' ';
			}
						
			var table_id = jQuery( this ).data( 'table_id' );
			if ( total_price[ table_id ] !== undefined ) {
				
				var t_total_price = total_price[ table_id ].toFixed( decimals );
								
				t_total_price = b2_format_price( t_total_price, d_separator, t_separator );
				
				jQuery( this ).text( t_total_price );
			}
		} );
		
		jQuery( ".wcbvp_total_quantity" ).each(function( i ) {
			
			var table_id = jQuery( this ).data( 'table_id' );
			if ( total_quantity[ table_id ] !== undefined ) {
				jQuery( this ).text( total_quantity[ table_id ] );
				
				if ( total_quantity[ table_id ] > 0 && product_list[ table_id ] !== undefined ) {
					
					jQuery( '#wcbvp_wrapper_' + table_id + ' .single_add_to_cart_button' ).removeClass( 'disabled' );
					jQuery( '#wcbvp_wrapper_' + table_id + ' .single_add_to_cart_button' ).removeClass( 'wc-variation-selection-needed' );
					jQuery( '#wcbvp_wrapper_' + table_id + ' .single_add_to_cart_button' ).prop( "disabled", false );
					
					jQuery( '#wcbvp_wrapper_' + table_id + ' [name="multiple-add-to-cart"]' ).val( product_list[ table_id ].toString() );
				}
				else {
				
					jQuery( '#wcbvp_wrapper_' + table_id + '.single_add_to_cart_button' ).addClass( 'disabled' );
					jQuery( '#wcbvp_wrapper_' + table_id + '.single_add_to_cart_button' ).addClass( 'wc-variation-selection-needed' );
					jQuery( '#wcbvp_wrapper_' + table_id + '.single_add_to_cart_button' ).prop( "disabled", true );
				}
			}			
		} );
	}
	
	jQuery('#product-row-single-attribute > td input').on('input click keydown keyup', function() {
		var index = jQuery(this).parent().index();
		// console.log(index);
		var nextChild = index + 1;
		// console.log(jQuery(this).val());
		var getQty = jQuery(this).val();
		jQuery("#product-row-variation-images > td:nth-child("+ nextChild +") .size-guide .xyz > .inner-size:last-child > span:nth-child(2)").each(function() {
			jQuery(this).nextAll("span").remove();
			var getSizes = Number(jQuery(this).text());		
			var showNewValue = getSizes * getQty;
			if(getQty > 0)
			{
				jQuery(this).hide();
				jQuery(this).after("<span>"+showNewValue+"</span>");
			}
			else
			{
				jQuery(this).nextAll("span").remove();
				jQuery(this).show();
			}
		});
		
	});

} );