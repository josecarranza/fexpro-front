<?php 
$username = "ftpfexpro";
$password = "WP820.1.com";
$server = "wwwfexpro.eastus2.cloudapp.azure.com";
try {
    $con = ftp_connect($server);
    if (false === $con) {
        throw new Exception('Unable to connect');
    }

    $loggedIn = ftp_login($con,  $username,  $password);
    if (true === $loggedIn) {
        echo 'Success!';
    } else {
        throw new Exception('Unable to log in');
    }

    var_dump(ftp_nlist($con, "."));
    ftp_close($con);
} catch (Exception $e) {
    echo "Failure: " . $e->getMessage();
}

 ?>