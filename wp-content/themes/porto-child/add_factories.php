<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../wp-load.php');
$return_array = array();
$return_array1 = array();
$return_array2 = array();
//global $wpdb;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Fexpro - Purchase content</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="jquery_script.js"></script>
	<style>
	input, textarea{width: 100%}
	table#demo tr > td:first-child {
		width: 20%;
	}
	[role="alert"]{display: none;}
	.submit{margin-bottom: 15px;}
	.red{border-color: #f00;}
	</style>
</head>
<body>
<h2>Add Factory</h2>
<div class="container">
<div class="alert alert-danger" role="alert">
  Atleast factory name is required.
</div>
<table class="table table-bordered" id="demo">
	<tbody>
        <tr>
			<td style="vertical-align : middle;">Factory code</td>
			<td><input type="text" class='fcode'/></td>
		</tr>
		<tr>
			<td style="vertical-align : middle;">Factory name</td>
			<td><input type="text" class='fname'/></td>
		</tr>
		<tr>
			<td style="vertical-align : middle;">Supplier Slug</td>
			<td><input type="text" class='supplier_slug'/></td>
		</tr>
		<tr>
			<td style="vertical-align : middle;">Factory address</td>
			<td><input type="text" class='faddress' /></td>
		</tr>
		<tr>
			<td style="vertical-align : middle;">Contact person</td>
			<td><input type="text" class='fperson'/></td>
		</tr>
		<tr>
			<td style="vertical-align : middle;">Phone no.</td>
			<td><input type="text" class='fphone1'/></td>
		</tr>
		<tr>
			<td style="vertical-align : middle;">Phone no. 2</td>
			<td><input type="text" class='fphone2'/></td>
		</tr>
		<tr>
			<td style="vertical-align : middle;">Email address</td>
			<td><input type="email" class='femail'/></td>
		</tr>
	</tbody>
</table>
<button class="submit" value="SUBMIT">SUBMIT</button>
<div class="alert alert-success" role="alert">
  Add Successfully.
</div>


</div>

<script>
$(document).ready(function(){
	jQuery(".submit").on('click', function() {
		var form_data = new FormData();	
		
		var fcode = $(".fcode").val();
		var fname = $(".fname").val();
		var supplier_slug = $(".supplier_slug").val();
		var faddress = $(".faddress").val();
		var fperson = $(".fperson").val();
		var fphone1 = $(".fphone1").val();
		var fphone2 = $(".fphone2").val();
		var femail = $(".femail").val();
		
		form_data.append('fcode', fcode);
		form_data.append('fname', fname);
		form_data.append('supplier_slug', supplier_slug);
		form_data.append('faddress', faddress);
		form_data.append('fperson', fperson);
		form_data.append('fphone1', fphone1);
		form_data.append('fphone2', fphone2);
		form_data.append('femail', femail);
		form_data.append('action', 'custom_add_factory');
		
		if(fname == '')
		{
			jQuery(".alert-danger").show();
			setTimeout(function() {
				jQuery(".alert-danger").hide();
			},2000);
			jQuery(".fname").addClass('red');
		}
		else
		{
			jQuery(".fname").removeClass('red');
		
		
		
		
		$.ajax({
			type: "POST",
			url: "https://shop.fexpro.com/wp-admin/admin-ajax.php",
			contentType: false,
			processData: false,
			data: form_data,
			success:function(msg) {
				console.log(msg);
				jQuery(".alert-success").show();
				setTimeout(function() {
					jQuery(".alert-success").hide();
				},500);
				jQuery("input, textarea").val('');
			},
			error: function(errorThrown){
				console.log(errorThrown);
				console.log('No update');
			}
		});
		}
	});
});
</script>
</body>
</html>
