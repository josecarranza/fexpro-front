<div class="wi-static-filter-element">
		<?php switch ($type) {
			case 'select':
				echo '<select data-url_param="'.$url_param.'" data-final_url="'.$final_url.'">
						<option>Select</option>';
				foreach ($values as $key => $value) {
					 echo '<option value="'.$value["value"].'" '.($selected==$value["value"] || $default==$value["value"] ?"selected" :"").'>'.$value["label"].'</option>';
				}
				echo '</select>'; 
			break;
			case 'radio':
				echo '<ul data-url_param="'.$url_param.'" data-final_url="'.$final_url.'">';
				foreach ($values as $key => $value) {
					echo '<li><input type="radio" name="'.$url_param.'" value="'.$value["value"].'" '.($selected==$value["value"] || $default==$value["value"] ?"checked" :"").' /> '.$value["label"].'</li>';
			   }
				echo '</ul>';
			break;
			case 'tag':
				echo '<ul class="woocommerce-widget-layered-nav-list">';
				foreach ($values as $key => $value) {
					echo '<li class="'.($selected!=$value["value"] ?: 'chosen').'"><a href="'.$final_url.($selected!=$value["value"]?"&".$url_param."=".$value["value"]:"").'">'.$value["label"].'</a></li>';
			   }
				echo '</ul>';
			break;
			case 'clear':
				echo '<div class="clear-filter"><a href="'.$url_clear.'">Clear filters</a></div>';
			break;
			case 'shopby':
				$q=get_queried_object();
				$parents=get_ancestors($q->term_id??0, 'product_cat');
				$q->slug =( in_array(5931,$parents) || 5931 == ($q->term_id??0))?"presale":($q->slug??"");
				$slug =  $q->slug?? "presale";
				$text = $slug=="presale"?"Pre sale":"Inventory";
				echo '<ul>';
				
					echo '<li><input type="radio" value="" checked class="shopby"  />'.$text.'</li>';
			
				echo '</ul>';
			break;
		}
		?>
</div>


<script>
	jQuery(document).ready(function ($) {
		$(".wi-static-filter-element > select").off("change");
		$(".wi-static-filter-element > select").change(function(){
			let final_url = $(this).data("final_url");
			final_url+="&"+$(this).data("url_param")+"="+$(this).val();
			location.href=final_url;
		});
		$(".wi-static-filter-element input[type='radio']").off("mousedown");
		$(".wi-static-filter-element input[type='radio']").on("mousedown",function(e){
			var $self = $(this);
			if($self.hasClass("shopby")){
				return false;
			}
  		if( $self.is(':checked') ){
			var uncheck = function(){
			setTimeout(function(){$self.removeAttr('checked');$self.trigger("change")},0);
			
			};
			var unbind = function(){
			$self.unbind('mouseup',up);
			};
			var up = function(){
			uncheck();
			unbind();
			};
			$self.bind('mouseup',up);
			$self.one('mouseout', unbind);
		
		}
		});
		$(".wi-static-filter-element input[type='radio']").off("change");
		$(".wi-static-filter-element input[type='radio']").change(function(){
			let final_url = $(this).parent().parent().data("final_url");
			if($(this).is(":checked")){
				final_url+="&"+$(this).parent().parent().data("url_param")+"="+$(this).val();
			}
			location.href=final_url;
		});
	});
</script>