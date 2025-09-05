<div class="wi-qep-container" ng-app="app-qep" ng-controller="ctrl">
    <div class="steps-panel">
        <div class="step-box active">
            <span class="step-number">1</span>
            <label for="">STEP 1</label>
            <label for="">Select items</label>
        </div>
        <div class="step-box">
            <span class="step-number">2</span>
            <label for="">STEP 2</label>
            <label for="">Checkout</label>
        </div>
        <div class="step-box">
            <span class="step-number">3</span>
            <label for="">STEP 3</label>
            <label for="">Confirmation</label>
        </div>
    </div>
    <h2>Quick shop</h2>
    <div class="row">
        <div class="col-8">
            <div class="tabs-quick-easy">
                <a href="<?=get_site_url()."/quick-easy-purchase/"?>?is_presale=1" class="{{is_presale && 'active'}}">Pre sale</a>
                <a href="<?=get_site_url()."/quick-easy-purchase/"?>?is_stock=1" class="{{!is_presale && 'active'}}">Inventory</a>
                <a href="<?=get_site_url()."/quick-easy-purchase/"?>?is_sale=1" class="d-none">Sale</a>
            </div>
        </div>
        <div class="col-4 text-right" ng-if="seller_mode">
            <div class="menu-seller-list ">
                <span class="text-btn-menu">My Lists <span class="ico-menu-bullets"></span></span>
                <div class="menu-seller-float-container">
                    <ul>
                        <li ng-repeat="list in list_user" ng-click="edit_list(list)">
                           {{list.name}}
                           <span class="remove-item" ng-click="remove_list($event,list)"></span>
                        </li>
                         
                        <li ng-click="edit_list(0)">
                            Create new list
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
    
  
    <div class="row mb-2">
        <div class="col-6">
            <label for="" class="lbl-qe-black">Select the following filters</label>
        </div>
        <div class="col-6 text-right">
            <a href="/quick-easy-purchase{{is_presale==1?'?is_presale=1':'?is_stock=1'}}" class="qe-link-filter">Clear filters <span class="ico-remove"></span></a>
            <span class="loading-spinner" ng-show="downloading_xlsx"></span>
            <a href="javascript:void(0)" class="qe-link-filter"  ng-click="export_xlsx()" ng-disabled="downloading_xlsx">Export to Excel <span class="ico-remove"></span></a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-7 col-xl-8">
            <div class="qe-filters-area d-flex">
                <util-select list="model.global_brands" ng-model="filtro['pa_global-brand']"  default-text="Brands" callback="update_url"></util-select>
                <util-select list="model.brands" ng-model="filtro.pa_brand"  default-text="Leagues" callback="update_url"></util-select>
                <util-select list="model.genders" ng-model="filtro.pa_gender"  default-text="Department" callback="update_url"></util-select>
                <util-select list="model.collections" ng-model="filtro.pa_collection"  default-text="Collection" callback="update_url" ng-show="is_presale==1"></util-select>
                <util-select list="model.products_type_apparel" ng-model="filtro.product_type_apparel"  default-text="Apparel" callback="update_url" ></util-select>
                <util-select list="model.products_type_accesor" ng-model="filtro.product_type_accesories"  default-text="Accesories" callback="update_url" ></util-select>
                <util-select list="model.delivery_dates" ng-model="filtro.meta_delivery_date"  default-text="Delivery" callback="update_url" include-all="true"></util-select>
            </div>
        </div>
        <div class="col-5 col-xl-4 text-right">
            <button class="btn-submit-qe" ng-click="aplicar_filtros()">Submit</button>
            <a href="/checkout?from=quick" class="btn-go-checkout-qe">Go to checkout</a>
        </div>
    </div>
  
    <br>
   <div ng-if="is_submit==1" ng-cloak >
        <div class="row">
            <div class="col-3">
                <label for="" class="title-blue">All collections</label>
            </div>
            <div class="col-9">
                <div class="qe-bar-grey">
                    <div class="text-right">
                        <a href="/checkout?from=quick" class="btn-go-checkout-qe">View cart</a>
                    </div>
                    <div class="pagination-bar">
                            <div class="numbers-container">
                                <a href="javascript:void(0)" ng-click="prev_pag()"><span class="ico-left"></span></a>
                                <a href="javascript:void(0)" class="{{p==pag && 'selected'}}" ng-repeat="p in pages_arr" ng-click="set_pag(p)">{{p}}</a>
                                <a href="javascript:void(0)" ng-click="next_pag()"><span class="ico-right"></span></a>
                            </div>
                            <div>
                                <b>{{total_items>per_page?per_page:total_items}} of {{total_items}} results</b>
                            </div>
                            <div class="selector-per-page">
                                Show
                                <select name="" id="">
                                    <option value="100">100</option>
                                </select>
                                entries
                            </div>
                        </div>
                    </div>
                </div>
        
        </div>
        <div class="mb-3 mt-3">
            <span class="checkbox"> <input type="checkbox" ng-click="select_all($event)"> Select all</span>
            <b ng-show="ids_selected.length>0">{{ids_selected.length}} references | {{ids_selected.length*24}} units selected</b>
            
            
        </div>
       
        <table class="qep-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Brand</th>
                    <th>Team</th>
                    <th>Department</th>
                    <th>Delivery</th>
                    <th ng-show="is_presale==0">Units available</th>
                    <th>Sizes</th>
                    <th class="text-left" style="width:192px">Packs</th>
                    <th>Subtotal</th>
                
                </tr>
            </thead>
            <tbody>
                <tr ng-if="model.products.length==0 && !getting_data">
                    <td colspan="10" class="text-center">No data available in table</td>
                </tr>
                <tr ng-if="getting_data">
                    <td colspan="10" class="text-center"><span class="loading-spinner"></span></td>
                </tr>
                <tr ng-repeat="row in model.products">
                    
                    <td width="200" style="white-space:nowrap">
                        <input type="checkbox" class="qe-table-check" ng-checked="row.selected" ng-click="select_one(row,$event)" />
                        <a href="{{row.product_url}}" target="_blank" class="img-prod">
                            <img  alt="" ng-src="{{row.image}}" style="margin-right:-14px" />
                            <img  alt="" ng-src="{{row.image2}}" ng-if="row.image2!=''" style="margin-left:-14px; margin-right:-14px" />
                        </a>
                    </td>
                    <td class="text-left">
                        <a href="{{row.product_url}}" target="_blank" class="title-product">
                        {{row.product_title2!='' ? row.product_title2: row.product_title}}
                        </a>
                        <div class="cell-data">
                            <span><b>SKU: </b></span>
                            <span>{{row.sku}}</span>
                        </div>
                        <div class="cell-data" ng-if="row.is_basic==1">
                            <span><b>Basic</b></span>
                        </div>
                        <div class="cell-data" ng-if="row.collection!=''">
                            <span><b>Collection: </b></span>
                            <span>{{row.collection}}</span>
                        </div>
                        
                        <div class="cell-data">
                            <span class="popup-text {{row.logo_application.length>80 && 'limit'}}">Logo application <div class="popup">{{row.logo_application || 'Data not available'}}</div></span>
                            <br />
                            <span class="popup-text {{row.composition.length>80 && 'limit'}}">Composition <div class="popup">{{row.composition || 'Data not available'}}</div></span>
                        </div>
                    </td>
                    <td><b>{{row.price | currency:'$'}}</b></td>
                    <td>{{row.brand}}</td>
                    <td>{{row.team}}</td>
                    <td>{{row.gender}}</td>
                    <td>
                        <div class="min-h extend-line {{(row.stock_future==0 && row.stock_present_china==0 )?'no-border':''}}" ng-show="row.stock_present>0" ng-if="is_presale==0">
                            IMMEDIATE {{row.stock_present_china>0 ? 'PANAMÁ' :''}}
                        </div>
                        <div class="min-h extend-line {{row.stock_future==0?'no-border':''}}" ng-show="row.stock_present_china>0" ng-if="is_presale==0">
                            IMMEDIATE CHINA
                        </div>
                        <div class="min-h no-border" ng-show="row.stock_future>0">
                        {{row.delivery_date | date:'MMMM d\'th\''}}
                        </div>
                            
                    </td>
                    <td ng-show="is_presale==0">
                        <div class="min-h extend-line {{(row.stock_future==0 && row.stock_present_china==0)?'no-border':''}}" ng-show="row.stock_present>0">
                            <span class="info-units">{{row.units_per_pack*row.stock_present}} units</span>
                        </div>
                        <div class="min-h extend-line {{row.stock_future==0?'no-border':''}}" ng-show="row.stock_present_china>0">
                            <span class="info-units">{{row.units_per_pack*row.stock_present_china}} units</span>
                        </div>
                        <div class="min-h no-border" ng-show="row.stock_future>0">
                            <span class="info-units">{{row.units_per_pack*row.stock_future}} units</span>
                        </div>
                    </td>
                    <td>
                        <div class="min-h extend-line {{(row.stock_future==0 && row.stock_present_china==0)?'no-border':''}}" ng-show="row.stock_present>0 && is_presale==0">
                            <div class="sizes">
                                <div ng-repeat="size in row.sizes"><i>{{size.size}}</i><i>{{size.value}}</i></div>
                                <div>
                                    <i>{{row.qty*row.units_per_pack}} units</i>
                                    <i>{{row.qty}} packs</i>
                                </div>
                            </div>
                        </div>
                        <div class="min-h extend-line {{row.stock_future==0?'no-border':''}}" ng-show="row.stock_present_china>0 && is_presale==0">
                            <div class="sizes">
                                <div ng-repeat="size in row.sizes"><i>{{size.size}}</i><i>{{size.value}}</i></div>
                                <div>
                                    <i>{{row.qty_china*row.units_per_pack}} units</i>
                                    <i>{{row.qty_china}} packs</i>
                                </div>
                            </div>
                        </div>
                        <div class="min-h no-border" ng-show="row.stock_future>0 || is_presale==1">
                            <div class="sizes">
                                <div ng-repeat="size in row.sizes"><i>{{size.size}}</i><i>{{size.value}}</i></div>
                                <div>
                                    <i>{{row.qty_future*row.units_per_pack}} units</i>
                                    <i>{{row.qty_future}} packs</i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="min-h extend-line {{(row.stock_future==0 && row.stock_present_china==0)?'no-border':''}}" ng-show="row.stock_present>0 && is_presale==0">
                            <div class="box-stepper">
                                <span class="added-tag" ng-show="row.added && row.qty==0">Added to cart</span>
                                <util-number-stepper ng-model="row.qty" max="row.stock_present"  ng-init="row.qty=0"></util-number-stepper>
                                <span class="qe-stock-available"><b>Available: </b>{{row.stock_present}}</span>
                                <div>
                                    <a class="qe-add-to-cart" ng-click="add_to_cart(row)" ng-show="!row.sending" ng-disabled="row.qty==0">Add to cart</a>
                                    <span class="loading-spinner" ng-show="row.sending"></span>
                                </div>
                            </div>
                        </div>
                        <div class="min-h extend-line {{row.stock_future==0?'no-border':''}}" ng-show="row.stock_present_china>0 && is_presale==0">
                            <div class="box-stepper">
                                <span class="added-tag" ng-show="row.added_china && row.qty_china==0">Added to cart</span>
                                <util-number-stepper ng-model="row.qty_china" max="row.stock_present_china"  ng-init="row.qty_china=0"></util-number-stepper>
                                <span class="qe-stock-available"><b>Available: </b>{{row.stock_present_china}}</span>
                                <div>
                                    <a class="qe-add-to-cart" ng-click="add_to_cart(row,false,true)" ng-show="!row.sending_china" ng-disabled="row.qty_china==0">Add to cart</a>
                                    <span class="loading-spinner" ng-show="row.sending_china"></span>
                                </div>
                            </div>
                        </div>
                        <div class="min-h no-border" ng-show="row.stock_future>0 || is_presale==1">
                            <div class="box-stepper">
                                <span class="added-tag" ng-show="row.added_future && row.qty_future==0">Added to cart</span>
                                <util-number-stepper ng-model="row.qty_future" max="is_presale==1?-1:row.stock_future"  ng-init="row.qty_future=0"></util-number-stepper>
                                <span class="qe-stock-available" ng-show="is_presale==0"><b>Available: </b>{{row.stock_future}}</span>
                                <div>
                                    <a class="qe-add-to-cart" ng-click="add_to_cart(row,true)" ng-show="!row.sending_future" ng-disabled="row.qty_future==0">Add to cart</a>
                                    <span class="loading-spinner" ng-show="row.sending_future"></span>
                                </div>
                            </div>
                        </div>
                        
                        
                    </td>
                    <td>
                        <div class="min-h extend-line {{(row.stock_future==0 && row.stock_present_china==0)?'no-border':''}}" ng-show="row.stock_present>0 && is_presale==0">
                            <b>{{row.price*row.qty*row.units_per_pack | currency:'$'}}</b>
                        </div>
                        <div class="min-h extend-line {{row.stock_future==0?'no-border':''}}" ng-show="row.stock_present_china>0 && is_presale==0">
                            <b>{{row.price*row.qty_china*row.units_per_pack | currency:'$'}}</b>
                        </div>
                        <div class="min-h no-border" ng-show="row.stock_future>0 || is_presale==1">
                        <b>{{row.price*row.qty_future*row.units_per_pack | currency:'$'}}</b>
                        </div>
                    </td>
                    
                
                </tr>
            </tbody>
        </table>

        <div class="row mt-3 mb-3">
            <div class="col-3">
                
            </div>
            <div class="col-9">
                <div class="qe-bar-grey">
                    <div class="text-right">
                        <a href="/checkout?from=quick" class="btn-go-checkout-qe">View cart</a>
                    </div>
                    <div class="pagination-bar">
                            <div class="numbers-container">
                                <a href="javascript:void(0)" ng-click="prev_pag()"><span class="ico-left"></span></a>
                                <a href="javascript:void(0)" class="{{p==pag && 'selected'}}" ng-repeat="p in pages_arr" ng-click="set_pag(p)">{{p}}</a>
                                <a href="javascript:void(0)" ng-click="next_pag()"><span class="ico-right"></span></a>
                            </div>
                            <div>
                                <b>{{total_items>per_page?per_page:total_items}} of {{total_items}} results</b>
                            </div>
                            <div class="selector-per-page">
                                Show
                                <select name="" id="">
                                    <option value="100">100</option>
                                </select>
                                entries
                            </div>
                        </div>
                    </div>
                </div>
        
        </div>
    </div>
    <div class="bar-edit-list" ng-show="edit_list_mode==1">
            <button class="btn-go-checkout-qe" style="background:#fff;margin-left:30px" ng-show="ids_selected.length>0" ng-click="add_items_to_list()">Add elements to list</button>
            <label for="" class="edit-model-list-name" >List: <b>{{current_list.name}}</b></label>
            <a class="btn-go-checkout-qe" href="/quick-easy-purchase" style="float:right">Cancel</a>
    </div>
    <div ng-if="is_submit==0">
        <br /><br /><br /><br /><br /><br />
    </div>
    <wi-modal show="show_modal"><div class="qep-alert-error" ng-cloak>{{modal_text}}</div></wi-modal>
    <div class="modal fade modal-js modal-quick-easy" tabindex="-1" role="dialog">
			
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
		    <div class="btn-modal-close"  data-dismiss="modal"></div>
		  
                <div class="modal-body">
                    <div class="text-center">
                    <span class="img-info"></span>
                    <label for="" class="lbl-blue">Welcome to Quick shop</label>
                    <p class="">Follow the steps to shop easier and select your order:</p>
                    </div>
                    <ul>
                    <li>Choose in what format you want to shop (pre-sale, stock)</li>
                    <li>Select your filters</li>
                    <li>Press the “Submit” button</li>
                    <li>Select the quantity of each item</li>
                    <li>Press the “Go to checkout” button to review your total before you pay</li>
                    </ul>
                    <div class="text-center">
                        <button class="btn-place-order"  ng-click="agree()">Agree</button>
                    </div>
				
			</div>
			 
		  </div>
		</div>
	  </div>
	

    <div class="modal fade modal-js modal-edit-list" tabindex="-1" role="dialog">
			
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
		  <div class="btn-modal-close"  data-dismiss="modal"></div>
		  
			<div class="modal-body">
				<div ng-show="edit_mode=='list'">
                    <div class="form-group">
                        <label for="">List name</label>
                        <input type="text" class="form-control" ng-model="form_list.name">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Image for list</label>
                                <select name="" id="" class="form-control" ng-model="form_list.image">
                                    <option value="">-Select-</option>
                                    <option value="{{img}}" ng-repeat="img in list_images">Imagen {{$index+1}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="" id="" class="form-control" ng-model="form_list.status">
                                    <option value="0">Inactive</option>
                                    <option value="1">Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="box-image-list" style="background-image:url('{{form_list.image}}')">

                            </div>
                        </div>
                    </div>

                 
                   
                    <div class="row">
                        <div class="col-4">
                            <button class="btn-submit-qe" type="button" style="margin-right:0px" ng-click="save_list()">Save</button>
                        </div>
                        <div class="col-8 text-right" ng-show="form_list.id_list>0">
                            <button class="btn-go-checkout-qe" type="button" style="background:#fff; height:34px; line-height:1" ng-click="go_add_items_list()"><span class="ico-add"></span></button>
                            <button class="btn-go-checkout-qe" type="button" style="background:#fff; height:34px; line-height:1" ng-click="go_remove_items_mode()"><span class="ico-edit"></span></button>
                            <button class="btn-go-checkout-qe" type="button" style="background:#fff; height:34px; line-height:1" ng-click="go_users_mode()"><span class="ico-user"></span></button>
                        </div>
                    </div>
                </div>
                <div ng-show="edit_mode=='items'">
                    <div class="row">
                        <div class="col-1">
                            <span class="ico-back" ng-click="edit_mode='list'"></span>
                        </div>
                        <div class="col-10">
                            <input type="text" class="input-search" placeholder="Search..." ng-model="item_search" ng-change="buscar_items($event)">
                        </div>
                    </div>
               
                    
                    <div class="text-center" style="padding:10px" ng-show="items_list_loading">
                        <span class="loading-spinner" ></span>
                    </div>
                    <div ng-show="!items_list_loading">
                        <button class="btn-go-checkout-qe" type="button" ng-click="remove_items_list()">Remove items selected</button>
                        <table class="table-edit-list">
                            <tr ng-repeat="row in items_list_edit_filter">
                                <td>
                                
                                    <input type="checkbox" ng-model="row.checked" ng-click="check_item_list(row,$event)">
                                </td>
                                <td>
                                    <img ng-src="{{row.image[0]}}" alt="" height="80">
                                </td>
                                <td>
                                    <a href="{{row.product_url}}" target="_blank" class="title-product">
                                    {{row.product_title}}
                                    </a>
                                    <div class="cell-data">
                                        <span><b>SKU: </b></span>
                                        <span>{{row.sku}}</span>
                                    </div>
                                    <div class="cell-data" ng-if="row.is_basic==1">
                                        <span><b>Basic</b></span>
                                    </div>
                                    <div class="cell-data" ng-if="row.collection!=''">
                                        <span><b>Collection: </b></span>
                                        <span>{{row.collection}}</span>
                                    </div>
                                
                                </td>
                            </tr>
                        </table>
                        <div>
                            <div class="numbers-container">
                        
                                <a href="javascript:void(0)" class="{{p==pag_list && 'selected'}}" ng-repeat="p in pagination_list" ng-click="set_pag2(p)">{{p}}</a>
                                
                            </div>
                        </div>
                    </div>

                </div>
                <div ng-show="edit_mode=='users'" class="panel-edit-mode-user">
                    <div class="row">
                        <div class="col-1">
                            <span class="ico-back" ng-click="edit_mode='list'"></span>
                        </div>
                        <div class="col-10" >
                            <input type="text" class="input-search" placeholder="Search by name or email..." ng-model="filter" ng-change="search_users($event)" style="margin-bottom:0px">
                            <div style="position:relative" ng-show="lista_users.length>0">
                                <div class="lista-buscar-usuarios">
                                    <div class="lista-item-buscar-usuario" ng-repeat="u in lista_users">                 
                                         
                                        <div class="lista-item-name" ng-click="addUserToList(u)">
                                            {{u.display_name}}
                                            <br>
                                            {{u.user_email}}
                                            <span>({{u.rol}})</span>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            
                        </div>
                    </div>
               
                    
                    <div class="text-center" style="padding:10px" ng-show="users_list_loading">
                        <span class="loading-spinner" ></span>
                    </div>
                    <div ng-show="!users_list_loading" style="margin-top:15px">
                        <button class="btn-go-checkout-qe" type="button" ng-click="remove_users_list()">Remove items selected</button>

                        <table class="table-edit-list">
                            <tr ng-repeat="row in usersoflist">
                                <td>
                                    <input type="checkbox" ng-model="row.checked" ng-click="row.checked = $event.target.checked">
                                </td>
                                 
                                <td>
                                     
                                    {{row.display_name}}
                                   
                                    <div class="cell-data">
                                       
                                        <span>{{row.user_email}}</span>
                                    </div>
                                 
                                </td>
                                <td> <span>{{row.rol}}</span></td>
                            </tr>
                        </table>

                        <div>
                            <div class="numbers-container">
                        
                                <a href="javascript:void(0)" class="{{p==pag_list && 'selected'}}" ng-repeat="p in pagination_list" ng-click="set_pag2(p)">{{p}}</a>
                                
                            </div>
                        </div>
                    </div>

                </div> 
			</div>
			 
		  </div>
		</div>
	  </div>
	</div>

</div>
<script src="<?=WI_PLUGIN_URL."assets/js/angular.min.js"?>"></script>
<script src="<?=WI_PLUGIN_URL."assets/js/angular-components.js?v=1"?>"></script>
<script src="<?=WI_PLUGIN_URL."assets/js/sweetalert2.all.min.js"?>"></script>
 
<link rel="stylesheet" href="<?=WI_PLUGIN_URL."assets/js/sweetalert2.min.css?v=1"?>">
<script>var site_url="<?=get_site_url();?>"</script>
<script>

var seller_mode=<?=$seller_mode?"true":"false"?>;
var edit_list_mode = <?=isset($_GET["edit_list_mode"])?"1":"0"?>;
var id_list = <?=isset($_GET["id_list"])?(int)$_GET["id_list"]:"0"?>;
</script>
<script>
var current_filters = <?=json_encode($current_filters)?>;
var collections =  <?=json_encode($collections)?>;

    angular.module("app-qep",["angular-components","util_components"]).controller("ctrl",function($scope,$http,$timeout){
        $scope.model={};
        $scope.model.categories=<?=json_encode($categories)?>;
        $scope.model.brands = <?=json_encode($brands)?>;
       
        $scope.model.collections = <?=json_encode($collections)?>;
        $scope.model.lob = <?=json_encode($lob)?>;
        $scope.model.delivery_dates = <?=json_encode($delivery_dates)?>;
        $scope.model.genders = <?=json_encode($genders)?>;
        $scope.model.groups = <?=json_encode($groups)?>;
        $scope.model.products_type_apparel = <?=json_encode($products_type_apparel)?>;
        $scope.model.products_type_accesor = <?=json_encode($products_type_accesor)?>;
        $scope.model.basics = <?=json_encode($basics)?>;
        $scope.model.global_brands = <?=json_encode($global_brands)?>;
        $scope.model.stock = "<?=$stock?>";
        $scope.is_presale = <?=$is_presale?1:0?>;
        $scope.select_presale = $scope.is_presale?"1":"0";
        $scope.is_submit = <?=$is_submit?>;
        $scope.model.products=[];

        $scope.all_ids=[];
        $scope.ids_selected=[];

        

        $scope.filtro={};
        $scope.filtro.pa_brand           = current_filters.pa_brand ?? [];
        $scope.filtro.pa_gender          = current_filters.pa_gender ?? [];
        $scope.filtro.pa_collection      = current_filters.pa_collection ?? [];
        $scope.filtro.product_type_apparel = current_filters.product_type_apparel ?? [];
        $scope.filtro.product_type_accesories = current_filters.product_type_accesories ?? [];
        $scope.filtro.meta_delivery_date =  current_filters.meta_delivery_date??[];
        $scope.filtro['pa_global-brand'] = current_filters['pa_global-brand'] ?? [];

        console.log($scope.filtro);
      

        $scope.pag=1;
        $scope.pages=1;
        $scope.pages_arr=[];
        $scope.total_items=0;
        $scope.per_page=20;

        $scope.getting_data=false;
        $scope.show_modal=false;
        $scope.modal_text="";

        $scope.seller_mode=seller_mode;

        $scope.aplicar_filtros=function(){
            url = location.origin+location.pathname;
            $scope.filtro.pag = $scope.pag;
            $scope.filtro.is_presale = $scope.is_presale;
            if(edit_list_mode==1){
                $scope.filtro.edit_list_mode=1;
                $scope.filtro.id_list=id_list;
            }
            //location.href=url+"?submit=1&"+jQuery.param($scope.filtros_selected);
            let filtro_params = angular.copy($scope.filtro);
            angular.forEach(filtro_params,(x,i)=>{
                if(x.length)
                    filtro_params[i]=x.join(",");
            });
            location.href=url+"?submit=1&"+jQuery.param(filtro_params);
        };
   
        $scope.remove_filter=function(slug,text){
            url = location.origin+location.pathname+location.search;
            if(text!='ONLY BASICS'){
                url=url.replace(slug,"");
            }else{
                url=url.replace("pa_only_basics=1","");
            }
            
            location.href=url;
        };

        $scope.get_data=function(){
            $scope.getting_data=true;
            $scope.model.products=[];
           
           // $scope.filtros_selected.pag=$scope.pag;

            $scope.filtro.pag = $scope.pag;
            $scope.filtro.is_presale = $scope.is_presale;
            //location.href=url+"?submit=1&"+jQuery.param($scope.filtros_selected);
            let filtro_params = angular.copy($scope.filtro);
            console.log(filtro_params);
            angular.forEach(filtro_params,(x,i)=>{
                if(x.length)
                    filtro_params[i]=x.join(",");
            });
            
            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_get_products",
                method:"GET",
                params:filtro_params
            }).then(function(response){
                if( $scope.pag==1){
                    $scope.pages=response.data.pages;
                    $scope.total_items = response.data.total;
                    $scope.all_ids = response.data.ids?response.data.ids.split(","):[];
                }
                if(response.data.products){
                    $scope.model.products=response.data.products;
                }else{
                    $scope.model.products=[];
                }
                $scope.pages_arr = paginate(3,$scope.pag,$scope.pages);
                $scope.getting_data=false;
                $scope.verify_selected();
            },function(){
                $scope.getting_data=false;
            });
        }
        $timeout(function(){
            if($scope.is_submit==1){
                $scope.get_data();        
            }
            if(localStorage.getItem("fex_qep_first_time")!="1"){
                $(".modal-quick-easy").modal();
            }
            
        },150);

        $scope.export_xlsx=function(){
            $scope.downloading_xlsx=true;
            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_get_products_export",
                method:"POST",
                data:{ids:$scope.ids_selected,is_presale:$scope.is_presale} //$scope.filtros_selected
            }).then(function(response){
                if(response.data.error==0){
                    window.open(response.data.download, '_blank');
                }
                $scope.downloading_xlsx=false;
            },function(){
                $scope.downloading_xlsx=false;
            });


        }

        $scope.add_to_cart=function(item,_is_future,_is_china){
            let is_future=_is_future?true:false;
            let is_china =_is_china?true:false;
            
            item.added=false;
            item.added_future=false;
            item.added_china=false;

            if(!is_future && !is_china && item.qty==0) return;
            if(!is_future && is_china && item.qty_china==0) return;
            if(is_future && item.qty_future==0) return;

            item.sending=(!is_future && !is_china)?true:false;
            item.sending_china=is_china?true:false;
            item.sending_future=is_future?true:false;
            
            $http({
                //url:site_url+"?wc-ajax=add_to_cart",
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_add_to_cart",
                method:"POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                data:jQuery.param({
                    quantity:!is_future?item.qty:0,
                    quantity_china:is_china?item.qty_china:0,
                    quantity_future:is_future?item.qty_future:0,
                    product_sku:item.sku,
                    variation_id:item.id,
                    product_id:item.main_id,
                    from_presale:$scope.is_presale
                })
            }).then(function(response){
                item.sending=false;
                item.sending_china=false;
                item.sending_future=false;
                if(response.data.error==0){
                    item.qty=0;
                    item.qty_china=0;
                    item.qty_future=0;
                    item.added=!is_future && !is_china?true:false;
                    item.added_china=is_china?true:false;
                    item.added_future=is_future?true:false;
                    //item.show_cart_link=true;

                }else{
                    $scope.modal_text=response.data.message;
                    $scope.show_modal=true;
                }
            },function(){
                item.sending=false;
            });
            
        };

 

        $scope.change_presale=function(){
            url = location.origin+location.pathname;
            if($scope.select_presale==1){
                location.href=url+"?is_presale=1";
            }else{
                location.href=url;
            }
            
        };
        $scope.$watchGroup(["filtro.pa_gender","filtro.pa_brand"],function(newval,oldval){
            //console.log(newval);
            $scope.filter_brands();
        },true);

        $scope.filter_brands = function(){
            let newval = $scope.filtro.pa_brand;
            if(newval==undefined || newval==[]){
                let _collect = angular.copy(collections);
                $scope.model.collections = _collect.map(x=>{
                    let _brands_line = x.brand.split(",").map(y=>y.split("||"));
                    let _brands = _brands_line.map(y=>y[1]);
                    x.text += _brands_line.length>0?" ("+_brands.join(", ")+")":"";
                    return x;
                });
            }else{
                let _collect = angular.copy(collections);
                $scope.model.collections=_collect.filter(x=>{
                    let _brands = x.brand.split(",").map(y=>y.split("||"));
                    return  _brands.filter(y=>newval.includes(y[0])).length>0;
                });
                if($scope.filtro.pa_gender.length){
                    $scope.model.collections=$scope.model.collections.filter(x=>{
                    let _department = x.department.split(",").map(y=>{y=y.split("||");y[0]=y[0].replace("-presale","");y[0]=y[0].replace("-stock-inmediato",""); return y;});
                    return  _department.filter(y=>$scope.filtro.pa_gender.includes(y[0])).length>0;
                });
                }
                $scope.model.collections=$scope.model.collections.map(x=>{
                    let _brands = x.brand.split(",").map(y=>y.split("||"));
                    _brands=_brands.filter(y=>newval.includes(y[0]));
                    x.text += " ("+_brands.map(y=>y[1]).join(", ")+")";
                    return x;
                });
                console.log($scope.model.collections);
            }
        }

        $scope.set_pag=function(p){
            if(p=="...")
            return;
            $scope.pag=Number(p);

            $scope.get_data();
        }
        $scope.prev_pag=function(){
            if($scope.pag==1){
                return;
            }
            $scope.set_pag(Number($scope.pag)-1);
        }
        $scope.next_pag=function(){
            if($scope.pag==$scope.pages){
                return;
            }
            $scope.set_pag(Number($scope.pag)+1);
        }

        $scope.select_all=function($event){
            
            if($event.target.checked){
                $scope.ids_selected=angular.copy($scope.all_ids);
                
            }else{
                $scope.ids_selected=[];
            }
            $scope.verify_selected();
        };
        $scope.verify_selected=function(){
            angular.forEach($scope.model.products,(item,index)=>{
                if($scope.ids_selected.includes(item.id)){
                    item.selected=true;
                }else{
                    item.selected=false;
                }
            });
        };
        $scope.select_one=function(item,$event){
            if($event.target.checked){
                item.checked=true;
                if(!$scope.ids_selected.includes(item.id)){
                    $scope.ids_selected.push(item.id);
                }
            }else{
                item.checked=false;
                if($scope.ids_selected.includes(item.id)){
                    $scope.ids_selected=$scope.ids_selected.filter(x=>x!==item.id);
                }
            }
        }

        $scope.agree=function(){
            localStorage.setItem("fex_qep_first_time","1");
            $(".modal-quick-easy").modal("toggle");
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        $scope.list_user=[];
        $scope.form_list={status:0};
        $scope.edit_list_mode=edit_list_mode;
        $scope.id_list = id_list;
        $scope.current_list={};
     
        $scope.list_images=[
            "https://shop2.fexpro.com/wp-content/plugins/wi-quick-easy-purchase/assets/images-list/list-1.jpg",
            "https://shop2.fexpro.com/wp-content/plugins/wi-quick-easy-purchase/assets/images-list/list-2.jpg",
            "https://shop2.fexpro.com/wp-content/plugins/wi-quick-easy-purchase/assets/images-list/list-3.jpg",
        ]

        $scope.edit_list=(_item)=>{
            if(_item){
                $scope.form_list=angular.copy(_item);
            }
            $scope.edit_mode='list';
            $(".modal-edit-list").modal("show");
        }
        $scope.remove_list=(e,_item)=>{
            e.stopPropagation();
            Swal.fire({
                title: 'Confirm',
                text:'Do you want remove this list?',
                confirmButtonText: 'Confirm', 
                showCloseButton: true,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $http({
                        url:site_url+"/wp-admin/admin-ajax.php?action=qep_list_remove",
                        method:"POST",
                        data:{id_list:_item.id_list} 
                    }).then((response)=>{
                        $scope.get_my_list();
                        //$scope.$apply();
                        Toast.fire({
                            icon: 'info',
                            title: 'List removed'
                        });
                    });
                }
            })
        }
        $scope.save_list= async ()=>{
            $scope.form_list.category=$scope.is_presale?"PRESALE":"INVENTORY";
            let response = await $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_list_save",
                method:"POST",
                data:$scope.form_list
            });
            if(response.data.error==0 && response.data.id_list){
                $scope.form_list.id_list = response.data.id_list;
                $scope.get_my_list();
                $scope.$apply();
                Toast.fire({
                    icon: 'success',
                    title: 'List saved'
                });
            }
        };
        $scope.get_my_list=()=>{
            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_list_user",
                method:"GET" 
            }).then((response)=>{
                $scope.list_user=response.data.lists??[];
            });
        }
        $scope.get_my_list();

        $scope.go_add_items_list=()=>{
            location.href=site_url+"/quick-easy-purchase/?edit_list_mode=1&id_list="+$scope.form_list.id_list+"&"+($scope.is_presale?"is_presale=1":"is_stock=1");
        }
        $scope.get_info_list=()=>{
            if($scope.edit_list_mode==1 && $scope.id_list>0){
                $http({
                    url:site_url+"/wp-admin/admin-ajax.php?action=qep_list_info&id_list="+$scope.id_list,
                    method:"GET" 
                }).then((response)=>{
                    $scope.current_list=response.data.list;
                });
            }
        }
        $scope.get_info_list();

        $scope.add_items_to_list=()=>{
            $http({
                    url:site_url+"/wp-admin/admin-ajax.php?action=qep_list_add_items&id_list="+$scope.id_list,
                    method:"POST",
                    data:{id_product:$scope.ids_selected }
                }).then((response)=>{
                    //$scope.ids_selected=[];
                    $scope.verify_selected();
                    
                    Toast.fire({
                        icon: 'success',
                        title: 'List saved'
                    });
                });
        };
        $scope.edit_mode='list';

        $scope.items_list_edit = [];
        $scope.items_list_edit_filter = [];
        $scope.pagination_list = [];
        $scope.pag_list = 1;
        $scope.pages2=1;
        $scope.per_page2=8;
        $scope.items_list_loading=false;
        $scope.lista_users = [];
        $scope.usersoflist = [];
        $scope.users_list_loading=false;

        $scope.go_remove_items_mode=()=>{
            $scope.edit_mode='items';
            $scope.get_items_list();
        };

        $scope.get_items_list = ()=>{
            $scope.pag_list=1;
            $scope.items_list_edit_filter = [];
            $scope.items_list_loading=true;
            $scope.pages2=1;

            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_get_products_export&format=json",
                method:"POST",
                data:{download_list:$scope.form_list.id_list}
            }).then((response)=>{
                $scope.items_list_loading=false;
                $scope.items_list_edit = response.data;
                $scope.items_list_edit=$scope.items_list_edit.map(x=>{
                    x.image[0] = site_url + "/wp-content/uploads/"+ x.image[0];
                    return x;
                });
                $scope.filtrar_items_lista();
            });
        }
        $scope.item_search='';
        $scope.buscar_items=()=>{
            $scope.pag_list=1;
            $scope.filtrar_items_lista();
        }
        $scope.filtrar_items_lista = ()=>{
            $scope.items_list_edit_filter = [];
            let li2 = ($scope.pag_list -1 ) * $scope.per_page2;
            let ls2 = (li2+$scope.per_page2);
            console.log(li2,ls2);
            let list_filter = $scope.items_list_edit.filter((x,i)=>{
                return (x.sku.search($scope.item_search)>-1 || x.product_title.search($scope.item_search) >-1);
            });
            console.log(list_filter);
             
            $scope.items_list_edit_filter = list_filter.filter((x,i)=>{
                return (i >= li2 &&  i< ls2);
            });

            $scope.pages2 = Math.ceil(list_filter.length/$scope.per_page2);
            $scope.pagination_list = paginate(3,$scope.pag_list,$scope.pages2);
        }

        $scope.set_pag2=function(p){
            if(p=="...")
            return;
            $scope.pag_list=Number(p);

            $scope.filtrar_items_lista();
            
        }
        $scope.prev_pag2=function(){
            if($scope.pag_list==1){
                return;
            }
            $scope.set_pag2(Number($scope.pag_list)-1);
        }
        $scope.next_pag2=function(){
            if($scope.pag_list==$scope.pages2){
                return;
            }
            $scope.set_pag2(Number($scope.pag_list)+1);
        }
        $scope.check_item_list=(item,event)=>{
            console.log(event);
            $scope.items_list_edit.map((_item)=>{
                
                if(_item.id == item.id ){
                    _item.checked = event.target.checked;
                }

                return _item;
            })
        };

        $scope.remove_items_list=()=>{
            let items = $scope.items_list_edit.filter(x=>x.checked).map(x=>x.id);
            Swal.fire({
                title: 'Confirm',
                text:'Do you want remove selected items?',
                confirmButtonText: 'Confirm', 
                showCloseButton: true,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $http({
                        url:site_url+"/wp-admin/admin-ajax.php?action=qep_list_items_remove",
                        method:"POST",
                        data:{id_list:$scope.form_list.id_list,items:items} 
                    }).then((response)=>{
                        $scope.get_items_list();
                        //$scope.$apply();
                        Toast.fire({
                            icon: 'info',
                            title: 'items removed'
                        });
                    });
                }
            })
        }

        $scope.go_users_mode = () =>{
            $scope.edit_mode='users';
            $scope.get_users_list();
        }

        $scope.get_users_list = ()=>{

            $scope.pag_list=1;
            //$scope.items_list_edit_filter = [];
            $scope.usersoflist = [];
            $scope.users_list_loading=true;
            $scope.pages2=1;

            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_list_users_get",
                method:"POST",
                data:{id_list:$scope.form_list.id_list}
            }).then((response)=>{
                $scope.users_list_loading=false;
                $scope.usersoflist = response.data.users;
                // $scope.items_list_loading=false;
                // $scope.items_list_edit = response.data;
                // $scope.items_list_edit=$scope.items_list_edit.map(x=>{
                //     x.image[0] = site_url + "/wp-content/uploads/"+ x.image[0];
                //     return x;
                // });
                // $scope.filtrar_items_lista();
            });
        }

        $scope.search_users = ()=>{
            if($scope.filter.length < 3 ){
                return false;
            }
            $http({
                url:site_url+"/wp-admin/admin-ajax.php?action=qep_search_users_get",
                method:"POST",
                data:{filter:$scope.filter}
            }).then((response)=>{

                $scope.lista_users= response.data.users;
         
            });
        };

        $scope.addUserToList = (_item) =>{
            let existe = $scope.usersoflist.filter(x=>x.ID == _item.ID);
    
            if(existe.length == 0){
                $scope.usersoflist.push(_item);
                $http({
                    url:site_url+"/wp-admin/admin-ajax.php?action=qep_add_users_to_list",
                    method:"POST",
                    data:{id_user:_item.ID,id_list:$scope.form_list.id_list}
                });
            }
            $scope.filter = "";
            $scope.lista_users = [];
        }
        $scope.remove_users_list = ()=>{
           
            var _toRemove = $scope.usersoflist.filter(x=>x.checked).map(x=>x.ID);
            $http({
                    url:site_url+"/wp-admin/admin-ajax.php?action=qep_remove_users_to_list",
                    method:"POST",
                    data:{id_list:$scope.form_list.id_list,items:_toRemove}
                }).then(()=>{
                    $scope.get_users_list();
                });
        }

    });

    
</script>
<script>
    $(document).ready(()=>{
        $(".menu-seller-list .text-btn-menu").click((e)=>{
            e.stopPropagation();
            let is_open=$(e.target).parent().hasClass("open");
        
            if(is_open){
                $(e.target).parent().removeClass("open");
            }else{
                $(e.target).parent().addClass("open");
            }
        });
        $("body").click((e)=>{
            if($(".menu-seller-list").hasClass("open")){
                setTimeout(()=>{
                    $(".menu-seller-list").removeClass("open");
                },100);
            }
        })
        $(".menu-seller-list .menu-seller-float-container ul li").click((e)=>{
            e.stopPropagation();
            setTimeout(()=>{
                $(".menu-seller-list").removeClass("open");
            },100);
        });
    })
    
</script>