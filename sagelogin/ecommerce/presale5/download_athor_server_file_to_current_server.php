<?php 
require_once 'include/common.php';
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



 //echo 'FTP connection was a success.';

 $directory = ftp_nlist($ftpConn,'/SageUpdatedInventory/');
 $remote_file = $directory[0];
 echo $remote_file."<BR>";
 $local_file = 'SageInv.Csv';

if ( ftp_get( $ftpConn, $local_file, $remote_file, FTP_BINARY ) ) {

    echo "WOOT! Successfully transfer $local_file\n";
}
else {
    echo "Doh! There was a problem\n";
}


}
ftp_close($ftpConn);



// $copy = copy( $remote_file_url, $local_file );
// if( !$copy ) {
//     echo "Doh! failed to copy $file...\n";
// }
// else{
//     echo "WOOT! success to copy $file...\n";
// }


?>