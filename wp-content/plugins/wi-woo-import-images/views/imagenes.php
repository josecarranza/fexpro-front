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

	<h1 class="wp-heading-inline">Import Images</h1>
	
	
	<br /><br />

<div class="woocommerce-progress-form-wrapper">

    <form action="edit.php?post_type=product&page=wi-import-images&action=imagenes" class="wc-progress-form-content woocommerce-importer" enctype="multipart/form-data" method="post">
   
	<header>
		<h2>Import Images</h2>
		<small>Los nombres de las imagenes deben ser el SKU del producto</small>
	</header>
	<section>
		<table class="form-table woocommerce-importer-options">
			<tbody>
				<tr>
					<th scope="row">
						<label for="upload">Select file (zip):</label>
					</th>
					<td>
							<input type="file" id="upload" name="import" size="25" accept=".zip" >
							<input type="hidden" name="action" value="importar" >
							
							<br>
							<small>
								Max size: <?=ini_get("upload_max_filesize");?>						</small>
												</td>
				</tr>
                <tr>
                    <td align="center" colspan="2">
                    <button type="submit" class="button button-primary button-next" value="Seguir" name="save_step">Upload</button>
                    
                    </td>
                </tr>
				
			</tbody>
		</table>
	</section>
	
	
</form>

</div>

<br>
<br>

	<table class="wp-list-table widefat fixed striped pages">
		<thead>
			<tr>
				<th scope="col"  class="manage-column column-title column-primary" width="60">
					<span>ID</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Nombre archivo</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Fecha carga</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Usuario carga</span>
				</th>
				<th scope="col"  class="manage-column column-title column-primary">
					<span>Status</span>
				</th>
			</tr>
		</thead>

		<tbody id="the-list">
			<?php foreach($lotes as $i => $lote): ?>
				<tr id="lote-<?=$lote->id_lote?>" status="<?=$lote->status ?>" class="row_lote" id_lote="<?=$lote->id_lote?>"> 
					<td><?=$lote->id_lote ?></td>
					<td><?=$lote->nombre_archivo ?></td>
					<td><?=$lote->fecha_carga ?></td>
					<td><?=$lote->usuario ?></td>
					<td><?=$lote->status ?>
					<?php if($lote->status=="PROCESANDO"):?>
					<span class="porcentaje">0%</span>
						<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
						<?php endif;?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>


	</div>
<script>
	jQuery(document).ready(function(){
		get_lotes_status();
	});

	function get_lotes_status(){
		dataPost=[];
		jQuery(".row_lote[status='PROCESANDO']").each(function(){
			dataPost.push(jQuery(this).attr("id_lote"));
		});
		jQuery.post("<?=get_site_url()?>/wp-admin/admin-ajax.php?action=info_lote_img",{lotes:dataPost},function(response){
			
			 for(i in response){
				jQuery("#lote-"+response[i].id_lote+" .porcentaje").text(response[i].procesado+"%");
				if(response[i].procesado==100){
					jQuery("#lote-"+response[i].id_lote+" .porcentaje").parent().html("FINALIZADO");
				}
			 }
			 setTimeout(function(){get_lotes_status()},5000);
		},"json");

	}
</script>