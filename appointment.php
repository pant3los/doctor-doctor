<?php
	include 'start.php';
  $ans = "";
  // Αν δεν είναι διαπιστευμένος Ιατρός, ασθενής ή γραμματέας, επιστροφή στην αρχική
  if (!(isset($_SESSION['email']))) {
    header('Location: index.php?redirect=unauthorized');
    exit;
  }
  $appointment_id = "";
  if (isset($_POST['completion'])){
    $appointment_id = $_POST['appointment_id'];
    $appointment = getAppointmentId($conn,$appointment_id);
    $health_problems = $_POST['health_problems'];
    $treatment = $_POST['treatment'];
    completeAppointment($conn, $appointment_id);
    setRecord($conn, $appointment['doctor_id'], $appointment['patient_id'], $health_problems, $treatment);
    $ans = "Το ραντεβού έχει ολοκληρωθεί. Μπορείτε να δείτε το ιστορικό του ασθενούς";
    //header('Location: patientrecord.php');
    //exit;
    $appointment = getAppointmentId($conn,$appointment_id);
  }
  else if (isset($_POST['cancel'])){
    $appointment_id = $_POST['appointment_id'];
    cancelAppointment($conn, $appointment_id);
    $appointment = getAppointmentId($conn,$appointment_id);
    $ans = "Το ραντεβού με id ".$appointment_id." ακυρώθηκε";
  }
  else if (isset($_POST['update'])){
    $appointment_id = $_POST['appointment_id'];
    $appointment = getAppointmentId($conn,$appointment_id);
    $doctor_id = $appointment['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_hour = $_POST['appointment_hour'];
    if (isAvailable($conn, $doctor_id ,$appointment_date, $appointment_hour)==1){
      updateAppointment($conn, $appointment_id,$appointment_date, $appointment_hour);
        $ans = "Το ραντεβού με id ".$appointment_id." ενημερώθηκε";
    }
    else{
      $ans = "Το ραντεβού με id ".$appointment_id." δεν ενημερώθηκε. Δεν ήταν διαθέσιμος ο γιατρός";
    }
    $appointment = getAppointmentId($conn,$appointment_id);
  }
  else if (isset($_GET['appointment_id'])){
    $appointment_id = $_GET['appointment_id'];
      $appointment = getAppointmentId($conn,$appointment_id);
      // Αν δεν υπάρχει καθόλου το ραντεβού, επιστροφή στην αρχική
      if ($appointment==-1){
        header('Location: index.php?redirect=noidappointment');
        exit;
      }

      if ($_SESSION['role']=='Ιατρός'){
        if ($doctor_id = $user['doctor_id']){
          if ($appointment['doctor_id']!=$doctor_id){
            header('Location: index.php?redirect=wrongidappointment');
            exit;
          }
        }
      }
      else if ($_SESSION['role']=='Ασθενής'){
        if ($patient_id = $user['patient_id']){
          if ($appointment['patient_id']!=$patient_id){
            header('Location: index.php?redirect=wrongidappointment');
            exit;
          }
        }
      }
  }
  else{ // Δεν έχει επιλεγεί ραντεβού, επιστροφή στην αρχική
    header('Location: index.php?redirect=noappointment');
    exit;
  }
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

 <?php
  if ($appointment['status']=='Ολοκληρωμένο'){ ?>
    <div class="w3-container" id="designers" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Ολοκληρωμένο Ραντεβού <?php echo $appointment_id;?></b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p> <?php
      echo $ans; 
      ?>
    </p>
  </div>
  <?php 
  }
  else{
 ?>
  
  <!-- Info -->
  <div class="w3-container" id="designers" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Πληροφορίες Ραντεβού <?php echo $appointment_id;?></b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p> <?php
      echo $ans; 
      ?>
    </p>
  </div>
  <!-- Update appointment form -->
  <form action="" method ="POST">
    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id'];?>">
    <div class="w3-section">
      <label>Ημερομηνία</label>
      <input class="w3-input w3-border" type="date" name="appointment_date" required value="<?php echo $appointment['appointment_date'];?>">
    </div>
    <div class="w3-section">
      <label>Ώρα</label>
      <input class="w3-input w3-border" type="time" name="appointment_hour" min="09:00" max="20:30" step="1800" required value="<?php echo $appointment['appointment_hour'];?>">
    </div>
    <button type="submit" name="update" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Ανανέωση</button>
  </form>
  <form action="" method="POST">
    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id'];?>">
    <button type="submit" name="cancel" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Ακύρωση</button>
  </form>

<div class="w3-container" id="designers" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Λόγος Ραντεβού <?php echo $appointment['reason'];?></b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p> Ασθενής:<strong>
      <?php echo $appointment['patient_fullname']." (".$appointment['security_number'].")";
      ?>
        
      </strong></p>
  </div>
<?php if ($_SESSION['role']=='Ιατρός'){
 ?>
  <div class="w3-container" id="contact" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Ολοκλήρωση ραντεβού.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    
    <form action="" method="POST">
      <input type="hidden" name="appointment_id" value="<?php echo $appointment['id'];?>">
      <div class="w3-section">
        <label>Ανιχνευμένα προβλήματα υγείας</label>
        <input class="w3-input w3-border" type="text" name="health_problems" required>
      </div>
      <div class="w3-section">
        <label>Θεραπεία</label>
        <input class="w3-input w3-border" type="text" name="treatment" required>
      </div>
      
      <button type="submit" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom" name="completion">Καταγραφή</button>
    </form>  
  </div>
<?php } ?>
  <!-- End page content -->
<?php }?>
</div>

<?php
	include 'end.php';
?>