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
    // Εισαγωγή των δεδομένων από το αρχείο CSV στον πίνακα slot
    $timeslot = $data[0].' '.$data[1];
    insertSlot($conn,$doctor_id, $timeslot);
    $count +=1;

}

// Κλείσιμο του αρχείου CSV
fclose($file);
header('Location: slots.php?redirect=success&count='.$count);
?>