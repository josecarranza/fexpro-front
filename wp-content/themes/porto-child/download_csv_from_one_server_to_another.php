<?php 
include('../../../wp-load.php');
error_reporting(E_ALL);

$host= 'wwwfexpro.eastus2.cloudapp.azure.com';
$user = 'ftpfexpro';
$password = 'WP820.1.com';
$ftpConn = ftp_connect($host);
$login = ftp_login($ftpConn,$user,$password);
// check connection
if ((!$ftpConn) || (!$login)) {
 echo 'FTP connection has failed! Attempted to connect to '. $host. ' for user '.$user.'.';
} else{
	$directory = ftp_nlist($ftpConn,'/SageUpdatedInventory/');
 	$remote_file = $directory[1];
 	$local_file = '/home/fexpro/public_html/shop/wp-content/themes/porto-child/SageInv.Csv';

	if ( ftp_get( $ftpConn, $local_file, $remote_file, FTP_BINARY ) ) {

	    echo "WOOT! Successfully transfer $local_file\n";
	}
	else {
	    echo "Doh! There was a problem\n";
	}

 

}

ftp_close($ftpConn);

?>