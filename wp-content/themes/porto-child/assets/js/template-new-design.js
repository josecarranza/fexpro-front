jQuery(document).ready(function($){
	$(".open-calendar-modal, .open-calendar-modal2, .open-calendar-modal3").click(function(){
		let _html=`
		<div class="modal fade modal-js" tabindex="-1" role="dialog">
			
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
		  <div class="btn-modal-close"  data-dismiss="modal"></div>
		  
			<div class="modal-body">
				`+$("#tmp-calendario").html()+`
			</div>
			 
		  </div>
		</div>
	  </div>
		`;
		$(".modal-js").remove();
		$("body").append(_html);
		$(".modal-js").modal();

	});

	$(".collection-accordion-header").click(function(){
		let isOpen = $(this).closest(".collection-accordion").hasClass("open");
		if(isOpen){
			$(this).closest(".collection-accordion").removeClass("open");
			$(this).closest(".collection-accordion").find(".collection-accordion-body").slideUp();
		}else{
			$(this).closest(".collection-accordion").addClass("open");
			$(this).closest(".collection-accordion").find(".collection-accordion-body").slideDown();
		}
	});
	$(".collection-accordion .collection-accordion-body").hide();
	$(".collection-accordion").eq(0).addClass("open");
	$(".collection-accordion:eq(0) .collection-accordion-body").slideDown();
	myList();
});

angular.module("util_components",[]);
angular.module("util_components").directive('clickAnywhereButHere', function() {
	return {
		restrict : 'A',
		link: { 
			post: function(scope, element, attrs) {
				element.on("click", function(event) {
					scope.elementClicked = event.target;
					$(document).on("click", onDocumentClick);
				});

				var onDocumentClick = function (event) {
					if(scope.elementClicked === event.target) {
						return;
					}
					scope.$apply(attrs.clickAnywhereButHere);
					$(document).off("click", onDocumentClick);
				};
			}
		}
	};
});
angular.module("util_components").component("utilSelect",{
	template:`
	<div class="util-comp-container">
		<div class="util-comp-select {{$ctrl.isOpen && 'open'}}" click-anywhere-but-here="close()">
			<div class="util-comp-select-text" ng-click="open()">
				{{$ctrl.text}}
			</div>
			<div class="util-comp-select-list-container">
				<ul>
					<li >
						<input type="checkbox" value="all" ng-checked="checked_all_check" ng-click="setAll($event)">
						<span>All</span>
					</li>
					<li ng-repeat="item in lista" class="{{item.color && 'color color-'+item.color}}" ng-click="set_parent_check($event,item)" >
						<input type="checkbox" value="{{item.value}}"  ng-model="item.checked" ng-true-value="'{{item.value}}'" ng-change="setModel(item)" >
						<span>{{item.text}}</span>
					
					</li>
				</ul>
			</div>
		</div>
	</div>
	`,
	bindings:{
		list:"=",
		ngModel:"=",
		defaultText:'@',
		callback:"=",
		filter:"=",
		includeAll:"="
	},
	controller:function($scope,$timeout){
		var $ctrl=this;
		$ctrl.isOpen=false;
		$ctrl.isAll=false;
		$scope.lista=[];
		$scope.checked_all_check=false;
		

		$ctrl.$onInit=function(){
			
			$ctrl.text = $ctrl.defaultText;
			if($ctrl.filter){
				$scope.lista=[];
				angular.forEach($ctrl.list,(item,index)=>{
					if($ctrl.filter==item.group){
						$scope.lista.push(angular.copy(item));
					}
				});
			}else{
				$scope.lista=angular.copy($ctrl.list);
			}

			if($ctrl.ngModel!=undefined  ){
				$scope.lista.map(x=>{
					x.checked=$ctrl.ngModel.find(y=>y==x.value)??false;
					return x;
				});
				//console.log($scope.lista)
				if($ctrl.isAll){
					$ctrl.text = "All";
				}else{
					$scope.setModel();
					$ctrl.text = $ctrl.ngModel.length>0?($ctrl.ngModel.length+" items selected"):$ctrl.defaultText;
				}
			}	
			
		}

		$scope.open=function(){
			$ctrl.isOpen=!$ctrl.isOpen;
		}
		$scope.close=function(){
			if($ctrl.isOpen && $ctrl.callback!=undefined){
				
				$ctrl.callback();
			}
			$ctrl.isOpen=false;
			
		}
		$scope.setAll=function($event){
			if($ctrl.includeAll){
				$scope.checked_all_check=$event.target.checked;
				if($event.target.checked){
					$ctrl.isAll=true;
				}else{
					$ctrl.isAll=false;
				
					$ctrl.ngModel.splice($ctrl.ngModel.findIndex(x=>x=="all"),1);
				}
			}else{
				$ctrl.isAll=!$ctrl.isAll;
			}
			
			
			if($ctrl.isAll){
				$scope.lista.map(x=>{
					x.checked=x.value;
					return x;
				});
			}else{
				$scope.lista.map(x=>{
					x.checked=false;
					return x;
				});
			}
			$scope.setModel();
		}
		$scope.$watch("$ctrl.ngModel",function(newval,oldvalue){
		
			if(newval!=undefined  ){
				$scope.lista.map(x=>{
					x.checked=newval.find(y=>y==x.value)??false;
					return x;
				});
				//console.log($scope.lista)
				if($ctrl.isAll){
					$ctrl.text = "All";
				}else{
					$ctrl.text = $ctrl.ngModel.length>0?($ctrl.ngModel.length+" items selected"):$ctrl.defaultText;
				}
			}	
			
		});
		$scope.$watch("$ctrl.list",function(newval){
			if(newval!=undefined  ){
				$ctrl.$onInit();
			}
		})
		
		$scope.setModel=function(){
			let is_prev_checked_all = $ctrl.ngModel.filter(x=>x=="all").length>0;
			if(is_prev_checked_all){
				$scope.checked_all_check=true;
			}
			$ctrl.ngModel=[];
			$ctrl.ngModel=$scope.lista.filter(x=>(x.checked!=false && x.checked!=null)).map(x=>x.value);
			if($ctrl.includeAll && $scope.checked_all_check){
				$ctrl.ngModel.push("all");
			}
			$ctrl.isAll = $ctrl.ngModel.length > 0 && $scope.lista.length == $ctrl.ngModel.length;
		};

		$scope.set_parent_check=function(e,item){
			e.stopPropagation();
			if($ctrl.ngModel.includes(item.value)){
				item.checked=null;
			}else{
				item.checked=item.value;
			}
			$scope.setModel();
		}
	}
});
angular.module("util_components").component("utilNumberStepper",{
	template:`
	<div class="units-buttons-container">
		<div class="units-buttons">
			<span class="btn-minus" ng-click="sub()"></span>
			<input type="number" min="0" ng-model="$ctrl.ngModel" ng-change="check()">
			<span  class="btn-plus" ng-click="add()"></span>
		</div>
	</div>
	`,
	bindings:{
		ngModel:"=",
		max:"="
	},
	controller:function($scope){
		var $ctrl=this;
		$scope.add=function(){
			if($ctrl.max>-1 && $ctrl.ngModel>=$ctrl.max){
				return;
			}
			$ctrl.ngModel++;
		}
		$scope.sub=function(){
			if($ctrl.ngModel<=0){
				return;
			}
			$ctrl.ngModel--;
		}
		$scope.check=function(){
			if($ctrl.max==-1)
				return;
			
			if($ctrl.ngModel>$ctrl.max){
				$ctrl.ngModel=Number($ctrl.max);
			}
		}
	}
});


function paginate(range, current, pages) {
	if (current >= pages - 2) {
	  range += 2;
	}
	if (pages < range) {
	  range = pages;
	}
	if (current > pages) {
	  current = pages;
	}
	start = 1;
	const paging = [];
	var i = Math.min(
	  pages + start - range,
	  Math.max(start, current - ((range / 2) | 0))
	);
	const end = i + range;
	while (i < end) {
	  paging.push(i === current ? `${i++}` : `${i++}`);
	}
  
	if (current < pages - 2) {
	  paging.push("...");
	  paging.push(pages);
	  //paging[range-1]=pages;
	}
	return paging;
  }
// jQuery(document).on("click",".util-comp-select-list-container ul li",function(e){
// 	setTimeout(function(){
// 		jQuery(this).find('input[type="checkbox"]').click();
// 	},100);
	
// 	e.stopPropagation();
// });

function myList(){
	$.get("/wp-admin/admin-ajax.php?action=qep_get_my_list",function(response){
		console.log(response);
		if(response.new_list>0){
			$(".panel-notification-ico-container").append('<span class="counter-list">'+response.new_list+'</span>');
		}
	},'json');
}