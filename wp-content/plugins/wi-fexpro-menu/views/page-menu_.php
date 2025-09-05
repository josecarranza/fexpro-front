<link href="<?=WI_PLUGIN_FEXPRO_MENU_URL."assets/css/bootstrap.min.css"?>" rel="stylesheet" />
<script src="<?=WI_PLUGIN_FEXPRO_MENU_URL."assets/js/angular.min.js"?>"></script>

<link href="<?=WI_PLUGIN_FEXPRO_MENU_URL."assets/css/wi-fexpro-menu.css"?>" rel="stylesheet"  />
<link href="<?=WI_PLUGIN_FEXPRO_MENU_URL."assets/js/jquery-ui.min.css"?>" rel="stylesheet"  />
<script src="<?=WI_PLUGIN_FEXPRO_MENU_URL."assets/js/sortable.js"?>"></script>

<div class="wi-fexpro-menu" ng-app="app" ng-controller="ctrl">
<div class="alert alert-success" role="alert" ng-show="alert_show">
  Menu Saved
</div>
	<div class="row">
		<div class="col-6">
			<h4>Fexpro menú</h4>
		</div>
	</div>
	<div class="row mb-5">
		<div class="col-4">
			<div class="form-group">
				<label for="">Select menú</label>
				<select name="id_menu" id="" class="form-control" ng-model="id_menu" ng-change="change_menu()">
				<?php foreach($menus as $menu):?>	
				<option value="<?=$menu->id_menu?>"><?=$menu->menu_name?> (ID <?=$menu->id_menu?>)</option>
				<?php endforeach;?>
				</select>
			</div>
		</div>
		<div class="col-12 mt-3">
			<button class="btn btn-primary" ng-click="guardar()">Save menú</button>
		</div>
	</div>
	<div class="wfmenu-row">
		<div class="wfmenu-col" >
			<div class="text-center">
				<h4>Level 0</h4>
			</div>
			<div ui-sortable="sortableOptions" ng-model="menu">
				<div ng-repeat="item in menu" ng-click="selectItem(0,$index)">
					<menu-item item="item" remove-item="removeItem(0,$index)"/>
				</div>
			</div>
			
			<div class="text-center">
			
				<button class="btn btn-outline-primary" ng-click="addItem(0)">Add item</button>
			</div>
		</div>
		<div class="wfmenu-col">
			<div class="text-center">
				<h4>Level 1</h4>
			</div>
			<div ui-sortable="sortableOptions" ng-model="menu_lvl1">
				<div ng-repeat="item in menu_lvl1" ng-click="selectItem(1,$index)">
					<menu-item item="item" remove-item="removeItem(1,$index)"/>
				</div>
			</div>
			<div class="text-center" ng-show="show_btn1">
				<button class="btn btn-outline-primary" ng-click="addItem(1)">Add item</button>
			</div>
		</div>
		<div class="wfmenu-col">
			<div class="text-center">
				<h4>Level 2</h4>
			</div>
			<div ui-sortable="sortableOptions" ng-model="menu_lvl2">
				<div  ng-repeat="item in menu_lvl2" ng-click="selectItem(2,$index)">
					<menu-item item="item" remove-item="removeItem(2,$index)"/>
				</div>
			</div>
			<div class="text-center" ng-show="show_btn2">
				<button class="btn btn-outline-primary" ng-click="addItem(2)">Add item</button>
			</div>
		</div>
		<div class="wfmenu-col">
			<div class="text-center">
				<h4>Level 3</h4>
			</div>
			<div ui-sortable="sortableOptions" ng-model="menu_lvl3">
				<div ng-repeat="item in menu_lvl3" ng-click="selectItem(3,$index)">
					<menu-item item="item" remove-item="removeItem(3,$index)"/>
				</div>
			</div>
			<div class="text-center" ng-show="show_btn3">
				<button class="btn btn-outline-primary" ng-click="addItem(3)">Add item</button>
			</div>
		</div>
	</div>
</div>
<script>
	var base_url="<?=get_site_url()?>";
	var id_menu = '<?=$id_menu ?? 0?>';
	var menu_json = <?=json_encode($menu_json)?>;
</script>
<script>
	angular.module("app",['ui.sortable'])
	.controller("ctrl",($scope,$http,$timeout)=>{
		$scope.model={};
		$scope.menu=menu_json??[];
		$scope.menu_lvl1=[];
		$scope.menu_lvl2=[];
		$scope.menu_lvl3=[];
		$scope.id_menu = id_menu;

		$scope.alert_show=false;

		$scope.show_btn1=false;
		$scope.show_btn2=false;
		$scope.show_btn3=false;
		

		$scope.addItem=(level)=>{
			if(level==0){
				$scope.menu.push({});
			}
			if(level==1){
				$scope.menu_lvl1.push({});
				$scope.menu = $scope.menu.map((x)=>{
					if(x.selected){
						x.items = $scope.menu_lvl1;
					}
					return x;
				})
			}
			if(level==2){
				$scope.menu_lvl2.push({});
				$scope.menu = $scope.menu.map((x)=>{
					if(x.selected){
						x.items = x.items.map((y)=>{
							if(y.selected){
								y.items = $scope.menu_lvl2;
							}
							return y;
						})
					}
					return x;
				})
			}
			if(level==3){
				$scope.menu_lvl3.push({});
				$scope.menu = $scope.menu.map((x)=>{
					if(x.selected){
						x.items = x.items.map((y)=>{
							if(y.selected){
								y.items  = y.items.map((z)=>{
									if(z.selected){
										z.items = $scope.menu_lvl3;
									}
									return z;
								})
							}
							return y;
						})
					}
					return x;
				})
			}
			$timeout(()=>{
					$scope.refreshOrder();
			},100);
		};
		$scope.selectItem=(level,index)=>{
			//console.log(level,index);
			// $scope.show_btn1=false;
			// $scope.show_btn2=false;
			// $scope.show_btn3=false;
			if(level==0){
				$scope.menu=$scope.menu.map(x=>{x.selected=false;return x});
				$scope.menu[index].selected=true;

				$scope.menu_lvl1=$scope.menu[index].items ?? [];
				//console.log($scope.menu_lvl1)
				if($scope.menu_lvl1.filter(x=>x.selected).length>0){
					$scope.menu_lvl2=$scope.menu_lvl1.filter(x=>x.selected)[0].items ?? [];
					if($scope.menu_lvl2.filter(x=>x.selected).length>0){
						$scope.menu_lvl3=$scope.menu_lvl2.filter(x=>x.selected)[0].items ?? [];
					}
				}else{
					$scope.menu_lvl2=[];
					$scope.menu_lvl3=[];
				}

				$scope.show_btn1=true;
			}
			if(level==1){
				$scope.menu = $scope.menu.map((x)=>{
					if(x.selected){
						x.items = x.items.map(y=>{y.selected=false; return y});
						x.items[index].selected=true;
						$scope.menu_lvl1=x.items;

						$scope.menu_lvl2=x.items[index].items ?? [];
						if($scope.menu_lvl2.filter(x=>x.selected).length>0){
							$scope.menu_lvl3=$scope.menu_lvl2.filter(x=>x.selected)[0].items ?? [];
						}else{
							$scope.menu_lvl3=[];
						}
					}
					return x;
				})
				$scope.show_btn2=true;
			}
			if(level==2){
				$scope.menu = $scope.menu.map((x)=>{
					if(x.selected){
						x.items.map(y=>{
							if(y.selected){
								y.items = y.items.map(z=>{z.selected=false; return z});
								y.items[index].selected=true;

								$scope.menu_lvl3=y.items[index].items ?? [];
							}
						});
						 
					}
					return x;
				})
				$scope.show_btn3=true;
			}
			if(level==3){
				$scope.menu = $scope.menu.map((x)=>{
					if(x.selected){
						x.items.map(y=>{
							if(y.selected){
								
								y.items.map(z=>{
									if(z.selected){
										z.items = z.items.map(za=>{za.selected=false; return za});

										z.items[index].selected=true;
									}
									

									
								});
								
							}
						});
						 
					}
					return x;
				})
			}
			//console.log($scope.menu);
		}

		$scope.removeItem=(level,index)=>{
			if(!confirm("Remove this menu item?")){
				return;
			}
			///console.log(level,index);
			if(level==0){
				$scope.menu.splice(index,1);
				$scope.menu_lvl1=[];
				$scope.menu_lvl2=[];
				$scope.menu_lvl3=[];
			}
			if(level==1){
				$scope.menu_lvl1.splice(index,1);
				$scope.menu=$scope.menu.map(x=>{
					if(x.selected){
						x.items = $scope.menu_lvl1;
					}
					return x;
				})
				$scope.menu_lvl2=[];
				$scope.menu_lvl3=[];
			}
			if(level==2){
				$scope.menu_lvl2.splice(index,1);
				$scope.menu=$scope.menu.map(x=>{
					if(x.selected){
						x.items = x.items.map(y=>{
							if(y.selected){
								y.items = $scope.menu_lvl2;
							}
							return y;
						});
					}
					return x;
				})
				
				$scope.menu_lvl3=[];
			}
			if(level==3){
				$scope.menu_lvl3.splice(index,1);
				$scope.menu=$scope.menu.map(x=>{
					if(x.selected){
						x.items = x.items.map(y=>{
							if(y.selected){
								y.items = y.items.map(z=>{
									if(z.selected){
										z.items = $scope.menu_lvl3;
									}
									return z;
								})
							}
							return y;
						});
					}
					return x; 
				})
			}
			$timeout(()=>{
					$scope.refreshOrder();
			},100);
		}
		$scope.sortableOptions ={
			delay: 150,
			update: function(e, ui) {
				//console.log($scope.menu);
				$timeout(()=>{
					$scope.refreshOrder();
				},100);
			}
		}
		$scope.refreshOrder=function(){
			console.log($scope.menu);

			/* $scope.menu_lvl1=$scope.menu_lvl1.map((x,i)=>{
				x.order=i;
				return x;
			}); */

			$scope.menu = $scope.menu.map((x,i1)=>{
				x.order =i1;
				if(x.items && x.selected){
					//let _items=[...x.items];
					if(x.selected){
						x.items = $scope.menu_lvl1;
					}
					x.items = x.items.map((y,i2)=>{
						y.order = i2;
						if(y.items && y.selected){
							if(y.selected){
								y.items = $scope.menu_lvl2;
							}
							y.items = y.items.map((z,i3)=>{
								z.order = i3;
								if(z.items && z.selected){
									if(z.selected){
										z.items = $scope.menu_lvl3;
									}
									z.items = z.items.map((za,i4)=>{
										za.order = i4;
										return za;
									})
								}
								return z;
							})
						}
						return y;
					})
				}
				return x;
			})
		}
		$scope.$watch('menu_lvl1',(newval)=>{
			//console.log(newval)
		},true);


		$scope.guardar=function(){

			let dataPost = {
				menu:$scope.menu,
				id_menu:$scope.id_menu
			}
			$http(
				{
					method:"POST",
					url:base_url+"/wp-json/api_menu/v1/save_menu",
					data:dataPost
				}
			).then((response)=>{
				if(response.data.error==0){
					$scope.alert_show=true;
					$timeout(()=>{
						location.reload();
					},3000);
				}
			})
		};

		$scope.change_menu=()=>{
			location.href=base_url+"/wp-admin/admin.php?page=wi_fexpro_menu&id_menu="+$scope.id_menu;
		}

	}).component("menuItem",{
		bindings:{
			item:"=",
			ngClick:"=",
			removeItem:"&"
		},
		controller:function ($scope){
			var $ctrl=this;
			$scope.model={};

			
			
			/* this.$onInit = function () {
				console.log($ctrl.item)
			}; */

			$scope.open=false;
			
			$scope.image="";
			
			$scope.img = function(hover){
				console.log($ctrl.item);

				window.imagePicker().then(function(response){
					
					if(response.url){
						if(hover!='hover'){

							$ctrl.item.image=response.url;
						}else{
							$ctrl.item.image_hover=response.url;
						}
						$scope.$apply();
					}
					//console.log($ctrl.item);
				})
			}
			$scope.$watch("$ctrl.item",(newval)=>{
				//console.log(newval);
			},true);

			$scope.removeItem=function(){
				$ctrl.removeItem();
			};
			$scope.removeImage = (hover)=>{
				if(hover!='hover'){
					$ctrl.item.image=null;
				}else{
					$ctrl.item.image_hover=null;
				}
			}
		},
		template:`<div class="wfmenu-item {{open && 'open'}} {{$ctrl.item.selected ? 'selected':''}}">
			<div class="wfmenu-item-header" ng-click="$ctrl.ngClick">
			<label for="">{{$ctrl.item.title ? $ctrl.item.title : '[new item]'}}</label>
			<spah class="ico-arrow-down" ng-click="open=!open"></spah>
			</div>
			<div class="wfmenu-item-body">
				<div class="form-group">
					<div class="row">
						<div class="col-6">
							<label for="">Title</label>
						</div>
						<div class="col-6">
						<input type="checkbox" ng-click="$ctrl.item.hide_title=!$ctrl.item.hide_title" ng-model="$ctrl.item.hide_title" /> hide title
						</div>
					</div>
					 
					<input type="text" class="form-control" ng-model="$ctrl.item.title">
				</div>
				<div class="form-group">
					<label for="">Link</label>
					<textarea name="" id=""  rows="3" class="form-control" ng-model="$ctrl.item.link"></textarea>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="">Image <a href="" class="pick-image" ng-click="img()" style="font-size:14px">Pick image</a></label>
							<div class="wfmenu-area-img" style="background-image:url('{{$ctrl.item.image}}')"></div>
							<div class="text-right">
								<span class="remove-item" ng-click="removeImage()" ng-show="$ctrl.item.image && $ctrl.item.image!=''">remove imagen</span>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="">Hover img <a href="" class="pick-image" ng-click="img('hover')" style="font-size:14px">Pick image</a></label>
							<div class="wfmenu-area-img" style="background-image:url('{{$ctrl.item.image_hover}}')"></div>
							<div class="text-right">
								<span class="remove-item" ng-click="removeImage('hover')" ng-show="$ctrl.item.image_hover && $ctrl.item.image_hover!=''">remove imagen</span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="">CSS Class</label>
					<input type="text" class="form-control" ng-model="$ctrl.item.css">
				</div>
				<div class="wfmenu-item-footer">
				<span class="remove-item" ng-click="removeItem()">remove menu item</span>
			</div>
			</div>
			
		</div>`
	});
</script>
<script>
	function imagePicker(){
		let p = new Promise((resolve,reject)=>{
			var image_frame;
			if(image_frame){
				image_frame.open();
			}
			// Define image_frame as wp.media object
			image_frame = wp.media({
							title: 'Select Media',
							multiple : false,
							library : {
								type : 'image',
							}
						});

				image_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = image_frame.state().get('selection').first().toJSON();
					//console.log(attachment)
					resolve(attachment);
				});

				image_frame.open();
		});

		return p;
	}

 
</script>