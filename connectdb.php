<?php      
    $host = "localhost";  
    $username =  "root" ;  
    $password = "";  
    $dbname = "doctordb";  
      
    $conn = mysqli_connect($host, $username, $password, $dbname);  
    if(mysqli_connect_errno()) {  
        die("MySQL Connection error: ". mysqli_connect_error());  
    }  
?>

