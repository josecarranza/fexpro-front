<?php include("header.php"); ?>

<form action="<?=get_site_url()?>/wp-admin/admin.php">
	<h3>Orders Panamá</h3>
	<input type="hidden" name="type" value="orders" />
	<input type="hidden" name="page" value="wi_fexpro_dashboard_orders_xml" />
	<div class="row">
		<div class="col-6">
			<div class="form-group">
				<label for="">Order Status</label>
				<select name="order_status" id="" class="form-control">
					<option value="">-Seleccione-</option>
					<?php foreach ($order_status as $item): ?>
						<option value="<?= $item['code'] ?>"><?= $item['name'] ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col-6">
			<label for=""> </label><br />
			<button class="btn btn-primary">Generar</button>
		</div>
	</div>
</form>
<br /><br />
<form action="<?=get_site_url()?>/wp-admin/admin.php">
	<h3>Orders Mexico</h3>
	<input type="hidden" name="type" value="orders_mex" />
	<input type="hidden" name="page" value="wi_fexpro_dashboard_orders_xml" />
	<div class="row">
		<div class="col-6">
			<div class="form-group">
				<label for="">Order Status</label>
				<select name="order_status" id="" class="form-control">
					<option value="">-Seleccione-</option>
					<?php foreach ($order_status as $item): ?>
						<option value="<?= $item['code'] ?>"><?= $item['name'] ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col-6">
			<label for=""> </label><br />
			<button class="btn btn-primary">Generar</button>
		</div>
	</div>
</form>