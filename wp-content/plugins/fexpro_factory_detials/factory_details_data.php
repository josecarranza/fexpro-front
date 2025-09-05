<?php 

  require_once dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-config.php';
  echo "<pre>";
  echo "database name". cstm_DB_NAME; echo "<br>";
  echo "database user".cstm_DB_USER;
  echo "</pre>";

 ?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
<style>
ul.factorys {
border: 1px solid #000;
border-radius: 5px;
box-shadow: 2px 2px 0px 2px #000;
}
ul.factorys li a {
text-decoration: none;
color: #fff;
font-size: 18px;
display: block;
padding: 15px;
background: #6e6e6e;
border-radius: 5px;
margin: 5px;
transition: all 0.2s ease;
text-transform: uppercase;
}
ul.factorys li a:hover {
background: #12323f;
}
</style>

</head>
<body>

<?php

$orders_urls=array(
"All Factory Order list"=>"https://shop.fexpro.com/wp-content/themes/porto-child/all_factory_ordered_placed_list.php",
"SUNSEA GARMENT CO. LTD"=>"https://shop.fexpro.com/wp-content/themes/porto-child/sunsea_germent_order_lists.php",
"NANCHANG XIN DONG YANG"=>"https://shop.fexpro.com/wp-content/themes/porto-child/nanchang_xin_dong_yang_order_lists.php",
"YANGZHOU YAXIYA HEADWEAR & GAR"=>"https://shop.fexpro.com/wp-content/themes/porto-child/yangzhou_yaxiya_order_lists.php",
"RION SPORTS PRODUCTS CO. LTD"=>"https://shop.fexpro.com/wp-content/themes/porto-child/rion_sports_order_lists.php",
"JINJIANG LAYNOS SPORTS GARMENT"=>"https://shop.fexpro.com/wp-content/themes/porto-child/jinjang_laynos_order_lists.php",
"QUANZHOU BEMY SPORTS CO.,LTD"=>"https://shop.fexpro.com/wp-content/themes/porto-child/quanzhou_bemy_sports_order_lists.php",
"NANCHANG HUAXING"=>"https://shop.fexpro.com/wp-content/themes/porto-child/nanchang_huaxing_order_lists.php",
"FUZHOU PRIME MERIDIAN"=>"https://shop.fexpro.com/wp-content/themes/porto-child/fuzhou_prime_meridian_order_lists.php",
"QUANZHOU MUTUZHE SPORTS"=>"https://shop.fexpro.com/wp-content/themes/porto-child/quanzhou_mutuzhe_sports_order_lists.php",
"ZELIN INDUSTRIAL CO., LTD."=>"https://shop.fexpro.com/wp-content/themes/porto-child/zenlin_factory_order_lists.php",
"NANCHANG KUNHAN"=>"https://shop.fexpro.com/wp-content/themes/porto-child/nanchang_kunhan_order_lists.php",
"NANCHNAG QUNFU"=>"https://shop.fexpro.com/wp-content/themes/porto-child/nanchang_qunfu_order_lists.php",
"QUANZHOU JUNTOUR GARMENTS"=>"https://shop.fexpro.com/wp-content/themes/porto-child/quanzhou_juntour_garments_order_lists.php",
"QUANZHOU USL BAGS"=>"https://shop.fexpro.com/wp-content/themes/porto-child/quanzhou_usl_bags_order_lists.php",
"JINJIANG KELUO"=>"https://shop.fexpro.com/wp-content/themes/porto-child/jinjiang_keluo_order_lists.php",
"ZHEJIANG TIANQI SOCKS"=>"https://shop.fexpro.com/wp-content/themes/porto-child/zhejiang_tianqui_socks_order_lists.php",
"TAIZHOU J&F HEADWEAR"=>"https://shop.fexpro.com/wp-content/themes/porto-child/taizhou_jf_headwear_order_lists.php",
"JINHUA OKADI"=>"https://shop.fexpro.com/wp-content/themes/porto-child/jinhua_okadi_order_lists.php",
"JINHUA CHENGXING"=>"https://shop.fexpro.com/wp-content/themes/porto-child/jinhua_chengxing_order_lists.php",
"K.K. Exim"=>"https://shop.fexpro.com/wp-content/themes/porto-child/kk_exim_order_lists.php",
"Dishang Group/Weihai Textile Group Import & Export Co,. Ltd"=>"https://shop.fexpro.com/wp-content/themes/porto-child/dishang_group_order_lists.php",
"QuanZhou TangRen Garments"=>"https://shop.fexpro.com/wp-content/themes/porto-child/quanzhou_tangren_garments_order_lists.php",
"JiangSu HongDo Group"=>"https://shop.fexpro.com/wp-content/themes/porto-child/jiangsu_hongdo_group_order_lists.php",
"FUJIAN TIME TRADING"=>"https://shop.fexpro.com/wp-content/themes/porto-child/fujian_time_trading_order_lists.php",
"Maylink Fujian Trading Co.,Ltd"=>"https://shop.fexpro.com/wp-content/themes/porto-child/maylink_fujian_trading_order_lists.php",
"NanChang PengXu"=>"http://shop.fexpro.com/wp-content/themes/porto-child/nanchang_pengxu_order_lists.php",
"NanChang DongShen"=>"http://shop.fexpro.com/wp-content/themes/porto-child/nanchang_dongshen_order_lists.php",
"ZELIN INDUSTRIAL CO., LTD."=>"https://shop.fexpro.com/wp-content/themes/porto-child/zenlin_factory_order_lists.php");


echo "<ul class='factorys'>";

		foreach($orders_urls as $key => $value)
		{
			//echo $value . "<br>";
			echo "<li>";
			echo "<a href='" . $value . "' target='_blank'>" . $key . "</a>";
			echo "</li>";
		}

echo "</ul>";
?>
	
</body>
</html> 