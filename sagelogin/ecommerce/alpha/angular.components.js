angular.module("util-components",[]);

angular.module("util-components")
.directive('clickAnywhereButHere', function () {
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
  })

angular.module("util-components")
.component("inputSave",{
	template:`<div class="input-edit-value2">
				<input type="text" ng-model="input_value" ng-change="update()" ng-disabled="$ctrl.disabled"  >
				<span class="btn-save" ng-click="save()" ng-show="false"><img src="ico-save.svg" alt="Save"></span>
			</div>`,
	bindings:{
		value:'=',
		row:'=',
		idVariation:'@',
		metaKey:'@',
		callback:'=',
		disabled:'='
	},
	controller:function($scope){
		var $ctrl = this;
		$scope.changed=false;
		$ctrl.$onInit = ()=>{
			//console.log($ctrl.callback);
			$scope.input_value=$ctrl.value??'';
		}
		
		$scope.update=()=>{
			$scope.changed=$ctrl.value!=$scope.input_value;
			$ctrl.row[$ctrl.metaKey] = $scope.input_value;
		}
		

		$scope.save=()=>{
			$ctrl.value = $scope.input_value;
			if(typeof $ctrl.callback == 'function'){
				$ctrl.callback({
					value:$scope.input_value,
					idVariation:$ctrl.idVariation,
					metaKey:$ctrl.metaKey
				});
				
			}
			$scope.changed=false;
		}
	}
});
angular.module("util-components").component("sageFilter2",{
	template:`<div class="filtro-col">
							<div class="filtro-col-container">
								
								<div class="filtro-col-wrapper">
									<ul>
										<li ng-repeat="item in $ctrl.list" class="ng-scope">
											<input type="checkbox" value="1" ng-model="item.checked" ng-change="upd()" class="ng-pristine ng-untouched ng-valid ng-empty"> 
											<span >{{item.text}}</span>
										</li>
									</ul>
								</div>
								<div class="filtro-col-btb">
									<button ng-click="apply_filters()">Apply</button>
								</div>
							</div>
						</div>`,
	bindings:{
		list:"="
	},
	controller:function($scope){
		$scope.upd=function(){
			$scope.$emit('update_filters');
		}
		$scope.apply_filters=function(){
			$scope.$emit('apply_filters');
		}
	}
}
);
angular.module("util-components").component("sageFilterText",{
	template:`<div class="filtro-col">
							<div class="filtro-col-container sage-filter-text">
								
								<div class="filtro-col-wrapper">
									 
										<input type="text" value="{{$ctrl.list[0].value}}" ng-model="$ctrl.list[0].value" ng-change="upd()" />
									 
									 
								</div>
								<div class="filtro-col-btb">
									<button ng-click="apply_filters()">Apply</button>
								</div>
							</div>
						</div>`,
	bindings:{
		list:"="
	},
	controller:function($scope){
		var $ctrl = this;
		$scope.upd=function(){
			if($ctrl.list[0].value !=""){
				$ctrl.list[0].checked = true;
			}else{
				$ctrl.list[0].checked = false;
			}
			$scope.$emit('update_filters');
		}
		$scope.apply_filters=function(){
			$scope.$emit('apply_filters');
		}
	}
}
);
angular.module("util-components").component("sageAutocomplete",{
	template:`<div class="sage-autocomplete" click-anywhere-but-here="handleClickOutside()">
					<div class="sage-autocomplete-value {{invalidValue ? 'invalid-value' : ''}}" ng-click="showList = !showList" title="{{$ctrl.value}}">
					{{$ctrl.value}}
					</div>
					<div class="sage-autocomplete-list" ng-if="showList">
						<div class="sage-autocomplete-searchbox">
							<input placeholder="Search..." ng-model="$ctrl.term" ng-change="search()" />
						</div>
						<div class="sage-autocomplete-list-container">
							<div class="sage-autocomplete-item {{item.key==$ctrl.value ? 'selected' :''}}" ng-repeat="item in list_filter" ng-click="setValue(item)">{{item.value}}</div>
						</div>
					</div>
				</div>`,
	bindings:{
		list:"=",
		value:"=",
		row:"=",
		metaKey:'@',
	},
	controller:function($scope){
		var $ctrl = this;
		$scope.showList = false;
		$scope.list_filter = [];
		$ctrl.term = "";
		$scope.invalidValue=false;
		$scope.search=()=>{
			if(!$scope.showList){
				return;
			}
			if($scope.term == ""){
				if($ctrl.list){
					$scope.list_filter = [...$ctrl.list];
				}
			}else{
				$scope.list_filter = [];
				if($ctrl.list){
					$ctrl.list.forEach(x=>{
						if(x.value.toLowerCase().includes($ctrl.term.toLowerCase())){
							$scope.list_filter.push(x);
						}
					})
				}
			}
		};
		$scope.$watch("showList",()=>{
			$scope.search();
		});
		// $scope.$watch("$ctrl.value",(newval)=>{
		// 	$scope.invalidValue=false;
		// 	if( $ctrl.list && $ctrl.value!=""){
		// 		$scope.invalidValue = $ctrl.list.filter((v)=>{v.key==$ctrl.value}).length==0;
		// 	}
		// });
		$scope.$watch("$ctrl.list",(newval)=>{
			$scope.valid_value();
		});
		$scope.valid_value=()=>{
			$scope.invalidValue=false;
			if($ctrl.list && $ctrl.value!=""){
				console.log($ctrl.value)
				$scope.invalidValue = $ctrl.list.filter((v)=>(v.key==$ctrl.value)).length==0;
			}
		}
		$scope.handleClickOutside=()=>{
			if($scope.showList){
				$scope.showList=false;
			}
		};
		$scope.setValue=(item)=>{
			$ctrl.value = item.key;
			$ctrl.row[$ctrl.metaKey] = item.key;
			$scope.showList = false;
			$scope.valid_value();
		}

	}

}
);