<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../wp-load.php');
global $wpdb;

$a = Array("49ERS" => "49E", "MLB" => "MLB", "BARCELONA" => "BAR", "BRAVES" => "BRA", "BUCCANEERS" => "BUC", "BULLS" => "BUL", "CELTICS" => "CEL", "CLIPPERS" => "CLI", "CUBS" => "CUB", "CHIEFS" => "CHI", "DODGERS" => "DOD", "DOLPHINS" => "DOL", "EAGLES" => "EAG", "GIANTS" => "GIA", "HEAT" => "HEA", "HORNETS" => "HOR", "JAZZ" => "JAZ", "KNICKS" => "KNI", "LAKERS" => "LAK", "MAVERICKS" => "MAV", "METS" => "MET", "NBA" => "NBA",  "NETS" => "NET", "NUGGETS" => "NUG", "PACKERS" => "PAC", "PATRIOTS" => "PAT", "PELICANS" => "PEL", "RAIDERS" => "RAI", "RAMS" => "RAM", "RAPTORS" => "RAP", "RAVENS" => "RAV", "RED SOX" => "RED", "ROCKETS" => "ROC", "SAINTS" => "SAI", "SEAHAWKS" => "SEA", "SPURS" => "SPU", "STEELERS" => "STE", "SUNS" => "SUN", "TRAIL BLAZERS" => "TRA", "WARRIORS" => "WAR", "YANKEES" => "YAN");

foreach($a as $key => $value)
{
	echo  $key . "<br>";
}

?>