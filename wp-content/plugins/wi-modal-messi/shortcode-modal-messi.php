<style>
	.modal-messi .btn-modal-close{
		position: absolute;
		display: inline-block;
		width: 30px;
		height: 30px;
		right: -15px;
		top: -15px;
		background: #fff url('data:image/svg+xml,<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><circle cx="12" cy="12" r="10" stroke="%231C274C" stroke-width="1.5"></circle><path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="%231C274C" stroke-width="1.5" stroke-linecap="round"></path></g></svg>') no-repeat center;
		background-size: containt;
		border-radius:50%;
		z-index: 1;
		cursor:pointer;

	}
	.ad-messi{
		width: 253px;
		height:700px;
		position: fixed;
		left:-253px;
		top:calc(50% - 350px);
		transition:left 0.5s;
		z-index: 2;

	}
	.ad-messi .ad-messi-content{
		position: relative;
		height:100%;
	}
	.ad-messi .ad-messi-content img{
		height:100%;
		width: auto;
	}
	.ad-messi .btn-ad{
		position: absolute;
		display: inline-block;
		width: 30px;
		height: 30px;
		right: -29px;
		border:1px solid #000;
	
		cursor: pointer;
		background: #fff url('data:image/svg+xml,<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="%23000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></g></svg>') no-repeat center;
		transform: rotate(0deg);
		transition:transform 0.5s;

	}
	.ad-messi.open{
		left:0px;
		transition:left 0.5s;
	}
	.ad-messi.open .btn-ad{
		transform: rotate(180deg);
		transition:transform 0.5s;
	}
</style>
<script>
	var url_imagen_messi = "<?=$url_imagen?>";
	var url_imagen_messi_ad = "<?=$url_imagen_ad?>";
	var link = "<?=$link?>";
	function modal_messi(){
		$ = jQuery;
		let _html=`
		<div class="modal fade modal-messi" tabindex="-1" role="dialog">
			
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		  <div class="modal-content">
		  <div class="btn-modal-close"  data-dismiss="modal" onclick="$('.modal-messi').modal('hide')"></div>
		  
			<div class="modal-body">
				<a href="${link}">
				<img src="${url_imagen_messi}" style="width:100%" />
				</a>
			</div>
			 
		  </div>
		</div>
	  </div>
		`;

		$(".modal-messi").remove();
		$("body").append(_html);
		$(".modal-messi").modal("show");
		$(".modal-messi").on('hide.bs.modal', function(){
			$(".ad-messi").addClass("open");
		});

		let _html2 = `
			<div class="ad-messi">
				<div class="btn-ad"></div>
				<div class="ad-messi-content">
					<a href="${link}">
						<img src="${url_imagen_messi_ad}" />
					</a>
				</div>
			</div>
		`;
		$("body").append(_html2);
		$(".ad-messi .btn-ad").click(function(){
			
			if($(".ad-messi").hasClass("open")){
				$(".ad-messi").removeClass("open");
			}else{
				$(".ad-messi").addClass("open");
			}
			
		});
	}
	 
	window.addEventListener('load', function () {
		modal_messi();
	}, false);
	
		
</script>