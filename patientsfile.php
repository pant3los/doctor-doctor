<?php
    session_start();
  include 'connectdb.php';
  include 'dbfunctions.php';
  if (!(isset($_SESSION['email']) && $_SESSION['role']=='Ιατρός')) {
    header('Location: index.php?redirect=unauthorized');
    exit;
  }
  else if (!(isset($_POST['select']) && isset($_FILES["csv_file"]))){
    header('Location: slots.php?redirect=nofile');
  }
  else{
    $user = getUserInfo($conn,$_SESSION['email']);
    $doctor_id = $user['doctor_id'];
  }

// Άνοιγμα του αρχείου CSV
$filename = $_FILES["csv_file"]["name"];
$filetmpname = $_FILES['csv_file']['tmp_name'];
$file = fopen($filetmpname, "r");

// Διάβασμα του αρχείου CSV γραμμή, γραμμή
$count = 0;
while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
    // Εισαγωγή των δεδομένων από το αρχείο CSV στον πίνακα patients
    $email = $data[0];
    $password = $data[1];
    $fullname = $data[2];
    $idcard = $data[3]; 
    $security_number = $data[4]; 
    if (getCountUsersEmail($conn, $email)==0){
      $sql = "INSERT INTO user (email, password, fullname, idcard, role) 
              VALUES ('$email', '$password', '$fullname', '$idcard', 'Ασθενής')";
      mysqli_query($conn, $sql);
      $user_id = mysqli_insert_id($conn);
      $sql = "INSERT INTO patient (user_id,  security_number, register_date) 
        VALUES ('$user_id', '$security_number', now())";
      mysqli_query($conn, $sql);
      $count +=1;
    }
    

}

// Κλείσιμο του αρχείου CSV
fclose($file);
header('Location: patientsadmin.php?redirect=success&count='.$count);
?>