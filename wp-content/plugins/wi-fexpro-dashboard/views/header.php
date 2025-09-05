<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.3/angular.min.js"></script>
<script src="<?=WI_PLUGIN_FEXPRO_DASHBOARD_URL."assets/js/components.js"?>"></script>
<link href="<?=WI_PLUGIN_FEXPRO_DASHBOARD_URL."assets/css/wi-fexpro-dashboard.css"?>" rel="stylesheet"  />	
<script src="<?=WI_PLUGIN_FEXPRO_DASHBOARD_URL."assets/js/highcharts.js"?>"></script>
<?php 
$page = $_GET["page"] ?? "wi_fexpro_dashboard";
?>
<div class="wi-fexpro-dashboard-menu">
	<ul class="nav">
		<li class="nav-item <?=$page == "wi_fexpro_dashboard" ? "active":""?>">
			<a class="nav-link "  href="<?=get_site_url()."/wp-admin/admin.php?page=wi_fexpro_dashboard"?>">Summary</a>
		</li>
		<li class="nav-item  <?=$page == "wi_fexpro_dashboard_products" ? "active":""?>">
			<a class="nav-link" href="<?=get_site_url()."/wp-admin/admin.php?page=wi_fexpro_dashboard_products"?>">Products</a>
		</li>
		<li class="nav-item  <?=$page == "wi_fexpro_dashboard_images" ? "active":""?>">
			<a class="nav-link" href="<?=get_site_url()."/wp-admin/admin.php?page=wi_fexpro_dashboard_images"?>">Images</a>
		</li>
		<li class="nav-item  <?=$page == "wi_fexpro_dashboard_orders" ? "active":""?>">
			<a class="nav-link" href="<?=get_site_url()."/wp-admin/admin.php?page=wi_fexpro_dashboard_orders"?>"  >Orders</a>
		</li>
	</ul>
</div>
