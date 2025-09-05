<style>
.woocommerce-progress-form-wrapper{
    background: #fff;
    overflow: hidden;
    padding: 0;
    margin: auto;
    box-shadow: 0 1px 3px rgba(0,0,0,.13);
    color: #555;
    text-align: left;
    max-width:500px;
    padding:30px;
}
.lds-ring {
  display: inline-block;
  position: relative;
  width: 30px;
  height: 30px;
  vertical-align: middle;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 30px;
  height: 30px;
  margin: 4px;
  border: 4px solid #fff;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #007cba transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

</style>
<div class="wrap">

<?php 
if(isset($_GET["status"]) && $_GET["status"]=="success"){
	message("success","Archivo importado con éxito");
}
if(isset($_GET["status"]) && $_GET["status"]=="error"){
	message("error","Error al leer el archivo");
}
if(isset($_GET["status"]) && $_GET["status"]=="error_formato"){
	message("error","Error al leer el archivo. Plantilla de carga incorrecta.");
}
 ?>

	<h1 class="wp-heading-inline">Sage - Logs de actualizaciónes</h1>
	
	
	<br><br>
	<a href="<?=get_site_url()."?task_get_stocks_manual=1"?>" class="button button-primary button-large">Iniciar actualización ahora</a>
	<br /><br />


	<table class="wp-list-table widefat fixed striped pages">
		<thead>
			<tr>
				<th scope="col"  class="manage-column column-title column-primary" width="60">
					<span>ID</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Fecha ejecución</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Fecha finalización</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Total items</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Items encontrados</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Items no encontrados</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Status</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Log</span>
				</th>
			</tr>
		</thead>

		<tbody id="the-list">
			<?php foreach($lotes as $i => $lote): ?>
				<tr id="lote-<?=$lote->id_log?>" status="<?=$lote->status ?>" class="row_lote" id_log="<?=$lote->id_log?>"> 
					<td><?=$lote->id_log ?></td>
					<td><?=$lote->fecha_ejecucion ?></td>
					<td><?=$lote->fecha_finalizacion ?></td>
					<td><?=$lote->items_total ?></td>
					<td><?=$lote->items_encontrados ?></td>
					<td><?=$lote->items_no_encontrados ?></td>
					<td><?=$lote->status ?></td>
					<td><a target="_blank" href="<?=get_site_url()."/".$lote->archivo_log?>">ver log</a></td>
				</tr>
			<?php endforeach; ?>
		</tbody>


	</div>
