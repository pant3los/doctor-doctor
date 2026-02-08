<?php
	include 'start.php';
  $ans = "";
  $redirect="";
  // Αν δεν είναι διαπιστευμένος Ιατρός ή Γραμματέας, επιστροφή στην αρχική
  if (!(isset($_SESSION['email'])) && $_SESSION['role']!='Ασθενής') {
    header('Location: index.php?redirect=unauthorized');
    exit;
  }
  if (isset($_POST['records'])){
    $patient_id = $_POST['patient_id'];
    header('Location: patientrecord.php?patient_id='.$patient_id);
    exit;
  }
  if (isset($_GET['redirect'])){
    if ($_GET['redirect']=='nofile'){
      $redirect = 'Πρέπει να επιλεγεί αρχείο';
    }
    else if ($_GET['redirect']=='success'){
      $redirect = 'Εισήχθησαν '.$_GET['count'].' ασθενείς';
    }
  }
  $query ="";
  if (isset($_POST['searchquery'])){
    $query = $_POST['query'];
    $patients = getAllPatientsQuery($conn,$query);
  }
  else if (isset($_POST['delete'])){
    $patient_id = $_POST['patient_id'];
    if (deletePatient($conn,$patient_id)==1){
      $ans = "Ο ασθενής διαγράφηκε επιτυχώς";
    }
    else{
      $ans = "Ο ασθενής έχει ενεργό ιστορικό ή ραντεβού. Δεν έγινε διαγραφή.";
    }

    $patients = getAllPatients($conn);
  }
  else{
    $patients = getAllPatients($conn);
  }
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

 
  
  <!-- Designers -->
  <div class="w3-container" id="designers" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Διαχείριση Ασθενών.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p>
      <?php 
      echo $ans;
      echo $redirect;
      ?>
    </p>
  </div>
  <form action="" method ="POST">
      <div class="w3-section">
        <label>ΑΜΚΑ / Επώνυμο</label>
        <input class="w3-input w3-border" type="text" name="query">
      </div>
      <button type="submit" name="searchquery" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Αναζήτηση Ασθενή</button>
    </form>
  
  <table border="2" bordercolor="red" width="100%">
      <tr>
        <th>α/α</th><th>Ονοματεπώνυμο</th><th>ΑΜΚΑ</th><th colspan="2">Ενέργειες</th>
      </tr>
      <?php
        $count = 1;
        foreach ($patients as $patient) {
          echo "<tr>";
            echo "<td>".$count."</td>";
            echo "<td>".$patient['fullname']."</td>";
            echo "<td>".$patient['security_number']."</td>";
           
            
            echo "<td>";
            echo '<form action="" method="POST">';
              echo '<input type="hidden" name="patient_id" value="'.$patient['id'].'">';
              echo '<button type="submit" name="records" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Ενημέρωση</button>';

              
            echo "</form>";
            echo "</td>";
            echo "<td>";
            echo '<form action="" method="POST">';
              echo '<input type="hidden" name="patient_id" value="'.$patient['id'].'">';
              echo '<button type="submit" name="delete" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Διαγραφή</button>';

            echo "</form>";
            
            echo "</td>";
          echo "</tr>";
          $count++;
        }
       ?>
       <tr><td></td><td></td><td colspan="3">
         
         <form action="patientsfile.php" method="POST" enctype="multipart/form-data">
      <div class="w3-section">
        <label>Αρχείο</label>
        <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
        <button type="submit" name="insert" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Μαζική Εισαγωγή</button>
      </div>
    </form>
       </td>
       </tr>
    </table>

<!-- End page content -->
</div>

<?php
	include 'end.php';
?>