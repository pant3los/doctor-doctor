<?php
	include 'start.php';
  $ans = "";
  $redirect= "";
  // Αν δεν είναι διαπιστευμένος, επιστροφή στην αρχική
  if (!(isset($_SESSION['email']))) {
    header('Location: index.php?redirect=unauthorized');
    exit;
  }
  // Διαγραφή εγγραφής
  if (isset($_POST['deleterecord'])){
    $record_id = $_POST['record_id'];
    deleteRecord($conn,$record_id);
    $ans = "Η εγγραφή ιστορικού διαγράφηκε";
  }
  $date="";
  $health="";
  if (isset($_POST['searchdate'])){
    $date = $_POST['register_date'];
    $ans = "Ιστορικό ημερομηνίας ".$date;
  }
  else if (isset($_POST['searchhealth'])){
    $health = $_POST['health_problems'];
    $ans = "Ιστορικό προβλήματος υγείας ".$health;
  }
  // Αν έχει γίνει ενημέρωση στοιχείων του ασθενή
  if (isset($_POST['updatepatient'])){
    $patient_id = $_POST['patient_id'];
    $fullname = $_POST['fullname'];
    $security_number = $_POST['security_number'];
    updatePatient($conn,$patient_id,$fullname,$security_number);
    $ans = "Τα στοιχεία του ασθενή ενημερώθηκαν";
  }
  // Αν είναι ασθενής, το id του ασθενή από το session
  if ($_SESSION['role']=='Ασθενής'){
    $patient_id = $user['patient_id'];
    $patient = getUserFromPatient($conn, $patient_id);
  }
  // Αν είναι γιατρός (ή γραμματέας) πρέπει να υπάρχει το id στο get (url)
  else if (isset($_GET['patient_id'])){
    $patient_id = $_GET['patient_id'];
    $patient = getUserFromPatient($conn, $patient_id);
    if ($patient==-1){
      header('Location: index.php?redirect=wrongidpatient');
      exit;
    }
  }
  

?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

 
  <!-- Search appointment form -->
  <div class="w3-container" id="contact" style="margin-top:75px">
    <div class="w3-container" id="appointment" style="margin-top:75px">
      <h1 class="w3-xxxlarge w3-text-red"><b>Ασθενής <?php echo $patient['fullname']." (".$patient['security_number'].")";?></b></h1>
      <hr style="width:50px;border:5px solid red" class="w3-round">
      <p>
        <?php 
        
          echo $ans;
        ?>
      </p>
    </div>
    <?php
    // Ο ασθενής δεν μπορεί να αλλάξει τα στοιχεία του
    if ($_SESSION['role']!='Ασθενής'){
    ?>
    <form action="" method="POST">
      <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
      <div class="w3-section">
        <label>Ονοματεπώνυμο</label>
        <input class="w3-input w3-border" type="text" name="fullname" value="<?php echo $patient['fullname'];?>" required>
      </div>
      <div class="w3-section">
        <label>ΑΜΚΑ</label>
        <input class="w3-input w3-border" type="text" name="security_number" value="<?php echo $patient['security_number'];?>" required>
      </div>
      
      <button type="submit" name="updatepatient" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Ενημέρωση</button>
    </form>  

    <?php
    }
    // Ο γραμματέας δεν μπορεί να δει το ιστορικό
    if ($_SESSION['role']!='Γραμματέας'){ ?>
      <h1 class="w3-xxxlarge w3-text-red"><b>Ιστορικό ασθενή</b></h1>
      <hr style="width:50px;border:5px solid red" class="w3-round">
      <form action="" method ="POST">
        <div class="w3-section">
          <label>Ημερομηνία</label>
          <input class="w3-input w3-border" type="date" name="register_date" required value="<?php echo $date;?>">
        </div>
        <button type="submit" name="searchdate" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom" >Αναζήτηση Ημερομηνία</button>
    </form>
    <form action="" method ="POST">
        <div class="w3-section">
          <label>Πρόβλημα Υγείας</label>
          <input class="w3-input w3-border" type="text" name="health_problems" required value="<?php echo $health;?>">
        </div>
        <button type="submit" name="searchhealth" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom" >Αναζήτηση Προβλήματος Υγείας</button>
    </form>
    <table border="2" bordercolor="red" width="100%">
      <tr>
        <th>α/α</th><th>Ιατρός</th><th>Ειδικότητα</th>
        <th>Ημερομηνία / Ώρα</th><th>Προβλήματα Υγείας</th><th>Θεραπείες</th>
        <?php 
          if ($_SESSION['role']=='Ιατρός'){
        ?>
          <th>Ενέργειες</th>
          <?php 
        } ?>
      </tr>
      <?php
        $count = 1;
        if ($date=="" && $health==""){
          $patientrecords = getPatientsRecords($conn,$patient_id);
        }
        else if ($date!=""){
          $patientrecords = getPatientsRecordsDate($conn,$patient_id,$date);
        }
        else{
          $patientrecords = getPatientsRecordsHealth($conn,$patient_id,$health);
        }
        if ($patientrecords==-1){
          echo "Δεν υπάρχουν εγγραφές ιστορικού";
        }
        else{
          foreach ($patientrecords as $record) {
            echo "<tr>";
              echo "<td>".$count."</td>";
              echo "<td>".$record['doctor_fullname']."</td>";
              echo "<td>".$record['specialization']."</td>";
              echo "<td>".$record['register_date']."</td>";
              echo "<td>".$record['health_problems']."</td>";
              echo "<td>".$record['treatment']."</td>";
              echo "<td>";
              if ($_SESSION['role']=='Ιατρός' && $count==1 && $date=="" && $health==""){
                
                echo '<form action="" method="POST">';
                  echo '<input type="hidden" name="record_id" value="'.$record['id'].'">';
                  echo '<button type="submit" name="deleterecord" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Διαγραφή</button>';

                  echo "</form>";
                }
                echo "</td>";

            echo "</tr>";
            $count++;
          }
        }
       ?>

    </table>
  <?php } ?>
  </div>

<!-- End page content -->
</div>

<?php
	include 'end.php';
?>