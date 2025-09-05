<?php  if(isset($_GET["empty_cart"]) && $_GET["empty_cart"]=="yes"){ ?>
<script>
    location.href="<?=get_site_url()?>/cart";
</script>
<?php } ?>
<style>
 a.import_xlsx { 
			text-align:right; 
			background: #70ad46; 
			position:relative; 
			padding: 13px; 
			font-size: 16px; 
			font-weight: 700;
			display: block;
    		color: #fff;
            display:inline-block;
            padding-left: 50px;
		}

		 a.import_xlsx:before {
		 content: ''; 
		background-image: url("data:image/svg+xml,%3Csvg version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' xmlns:xlink='http://www.w3.org/1999/xlink' enable-background='new 0 0 512 512' style='fill: %23fff%3B'%3E%3Cg%3E%3Cg%3E%3Cpath d='m153.7 171.5l81.9-88.1v265.3c0 11.3 9.1 20.4 20.4 20.4 11.3 0 20.4-9.1 20.4-20.4v-265.3l81.9 88.1c7.7 8.3 20.6 8.7 28.9 1.1 8.3-7.7 8.7-20.6 1.1-28.9l-117.3-126.2c-11.5-11.6-25.6-5.2-29.9 0l-117.3 126.2c-7.7 8.3-7.2 21.2 1.1 28.9 8.2 7.6 21.1 7.2 28.8-1.1z'/%3E%3Cpath d='M480.6 341.2c-11.3 0-20.4 9.1-20.4 20.4V460H51.8v-98.4c0-11.3-9.1-20.4-20.4-20.4S11 350.4 11 361.6v118.8 c0 11.3 9.1 20.4 20.4 20.4h449.2c11.3 0 20.4-9.1 20.4-20.4V361.6C501 350.4 491.9 341.2 480.6 341.2z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
		    position: absolute;
    width: 30px;
    height: 30px;
    left: 14px;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    top: 7px;
		}
        .message-import{
            color: #f00;
            font-size: 16px;
            margin-top: 30px;
        }
</style>
<div class="woocommerce">
    <div class="cart-empty-page text-center">
        
        <i class="cart-empty porto-icon-bag-2"></i>
        <p class="px-3 py-2 cart-empty">No products added to the cart</p>
        <p class="return-to-shop">
            <a class="button wc-backward btn-v-dark btn-go-shop" href="<?=get_site_url()?>/brand/">
                Return to shop </a>
        </p>
        <br>
        <div>
            OR
            <br>
            <br>
            <div class="">
		<a href="javascript:void(0);" class="import_xlsx" >Import Purchase Order</a>
        <div class="message-import" style="display:none"></div>
		<div style="display: none">
		<input type="file" id="file_xlsx" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="file_xlsx">
		</div>
	</div>
        </div>
    </div>
</div>
<script>var $ = jQuery;</script>
<script>var base_url="<?=get_site_url()?>";</script>
<script src="<?=get_site_url()?>/wp-content/themes/porto-child/ajax-upload.js"></script>
<script>
	$(".import_xlsx").click(function(event) {
		$("#file_xlsx").click();
		/* Act on the event */
	});
	$("#file_xlsx").change(function(e){
		file = $(this)[0].files[0];
		
        $(".message-import").hide();
		//if()
		$.ajax_upload({
			url:base_url+"/wp-admin/admin-ajax.php?action=import_order",
			file:file,
			dataType:"json",
			start:function(){},
			progress:function(data){
				console.log(data);
			},
			finish:function(data){
                if(data.error==0){
                    location.reload();
                }else{
                    $(".message-import").show();
                    $(".message-import").text(data.message);
                }
				
			}
		});
	});
</script>
