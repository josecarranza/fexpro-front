<style>
	.brands-container{
		width: 100%;
	}
	.brands-container .brand-group{
		width: 100%;
		margin-bottom:10px;
	}
	.brands-container .brand-group .brand-group-header{
		border-bottom:1px solid  #D9D9D9;
		padding:2px 2px;
		position: relative;
		display: block;
   		width: 100%;
	}
	.brands-container .brand-group .brand-group-header label{
		font-size:14px;
		color:#000;
		margin-bottom:0px;
		font-weight:500;
	}
	.brands-container .brand-group .brand-group-body ul li a{
		color:#16243E;
		font-size:13px;
		padding:2px;
		padding-left:20px;
		display:block;
		position: relative;
	}
	.brands-container .brand-group .brand-group-body ul li a:before{
		content:'';
		position: absolute;
		left: 0px;
    	top: 5px;
		display:inline-block;
		width: 14px;
		height:14px;
		border:1px solid #3C3939;
		border-radius:8px;
		
	}
	.brands-container .brand-group .brand-group-body ul li.chosen a:before{
		background:#3C3939;
	}
	.brands-container .brand-group .ico-arrow{
		position: absolute;
		right: 5px;
		top:8px;
		width: 10px;
		height:10px;
		cursor: pointer;
		display:inline-block;
		vertical-align:middle;

		border: solid #16243E;
		border-width: 0 1px 1px 0;		
		transform: rotate(225deg);

	}
	.brands-container .brand-group .closed .ico-arrow{
		transform: rotate(45deg);
	}
</style>
<div class="brands-container">
	<?php foreach($brands_list as $brand_key => $brand_title):?>
	<div class="brand-group">
		<div class="brand-group-header">
			<label for="<?=$brand_key?>"><?=$brand_title?></label>
			<span class="ico-arrow"></span>
		</div>
		<div class="brand-group-body">
			<ul>
				<?php 
				//$all_brand=isset($_GET["allbrand_".$brand_key]) && $_GET["allbrand_".$brand_key]==1? 1 : 0;
				$brands_of_group=[];
				$count=0;
				foreach($brands as $item_brand):
					if($brand_key==$item_brand->brand_group):
						$brands_of_group[]=$item_brand->slug;
						if( in_array($item_brand->slug,$current_brand)){
							$count++;
						}
					endif;
				endforeach;
				
				if(count($brands_of_group) == $count){
					$all_brand=1;
				}else{
					$all_brand=0;
				}

				if($all_brand==0){
					$new_current_brand=$current_brand;
					foreach($brands as $item_brand):
						if($brand_key==$item_brand->brand_group && !in_array($item_brand->slug,$new_current_brand)):
							$new_current_brand[]=$item_brand->slug;
						endif;
					endforeach;
					$segment_filter = "&filter_brand=".implode(",",$new_current_brand);
				}else{
					$new_current_brand=$current_brand;
					//$final_url = str_replace("allbrand_".$brand_key."=1","",$final_url);
					foreach($brands as $item_brand):
						if($brand_key==$item_brand->brand_group):
							$pos = array_search($item_brand->slug,$new_current_brand);
							unset($new_current_brand[$pos]);
						endif;
					endforeach;
					if(count($new_current_brand)>0){
						$segment_filter = "&filter_brand=".implode(",",$new_current_brand);
					}else{
						$segment_filter = "";
					}
				}
				
				?>
				<li class="<?=$all_brand==1?"chosen":""?>"><a href="<?=$final_url.$segment_filter?>">All</a></li>
				<?php 
					foreach($brands as $item_brand):
						if($brand_key==$item_brand->brand_group):

							$new_current_brand=$current_brand;
							$chosen = false;
							if(in_array($item_brand->slug,$new_current_brand)){
								$pos = array_search($item_brand->slug,$new_current_brand);
								unset($new_current_brand[$pos]);
								$chosen = true;
							}else{
								$new_current_brand[]=$item_brand->slug;
							}
							if(count($new_current_brand)>0){
								$segment_filter = "&filter_brand=".implode(",",$new_current_brand);
							}else{
								$segment_filter = "";
							}

				?>
							<li class="<?=$chosen?"chosen":""?>"><a href="<?=$final_url.$segment_filter?>"><?=$item_brand->name?></a></li>
				<?php
						endif;
					endforeach;
				?>
				
			</ul>
		</div>
	</div>
	<?php endforeach;?>
</div>
<script>
	$=jQuery;
	$(".brands-container .brand-group .ico-arrow").click(function(){
		if($(this).parent().hasClass("closed")){
			$(this).parent().removeClass("closed");
			$(this).parent().next().slideDown();
		}else{
			$(this).parent().addClass("closed");
			$(this).parent().next().slideUp();
		}
		
	});
</script>