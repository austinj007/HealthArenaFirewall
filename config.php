<?php

    $servername="localhost";
    $username="root";
    $password="";
    $dbname="healtharena_db";
    $con = mysqli_connect($servername,$username,$password,$dbname);

    if(!$con){
        die("Connection failed . ERROR ".mysqli_connect_error());
    }
else{
    echo "";
}
?>
