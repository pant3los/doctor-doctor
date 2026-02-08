<?php
	include 'start.php';
  $ans = "";
  $redirect= "";
  // Αν δεν είναι διαπιστευμένος, επιστροφή στην αρχική
  if (!(isset($_SESSION['email']))) {
    header('Location: index.php?redirect=unauthorized');
    exit;
  }
  if (isset($_POST['update'])){
    $appointment_id = $_POST['appointment_id'];
    header('Location: appointment.php?appointment_id='.$appointment_id);
    exit;
  }
  $date = date('Y-m-d');
  if (isset($_POST['searchdate'])){
    $date = $_POST['appointment_date'];
  }
  $query ="";
  if (isset($_POST['searchquery'])){
    $query = $_POST['query'];
    if ($_SESSION['role']=='Ιατρός'){
      $doctor_id = $user['doctor_id'];
      $appointments = getAllAppointmentsDoctorQuery($conn,$query,$doctor_id);
    }
    else{
      $appointments = getAllAppointmentsQuery($conn,$query);
    }
  }
  else if ($_SESSION['role']=='Ιατρός'){
    $doctor_id = $user['doctor_id'];
    $appointments = getAllAppointmentsDoctor($conn,$doctor_id,$date);
  }
  else if ($_SESSION['role']=='Ασθενής'){
    $patient_id = $user['patient_id'];
    $appointments = getAllAppointmentsPatient($conn,$patient_id,$date);
  }
  else if ($_SESSION['role']=='Γραμματέας'){
    $appointments = getAllAppointments($conn,$date);
  }
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

 
  <!-- Search appointment form -->
  <div class="w3-container" id="contact" style="margin-top:75px">
    <div class="w3-container" id="appointment" style="margin-top:75px">
      <h1 class="w3-xxxlarge w3-text-red"><b>Τα ραντεβού μου.</b></h1>
      <hr style="width:50px;border:5px solid red" class="w3-round">
      <p>
        <?php 
        // Δεν έχει γίνει αναζήτηση ασθενή, εμφάνιση ημερομηνίας
          if ($query==""){
            echo 'Ημερομηνία εμφάνισης: <strong>'.$date."</strong></br>";
          }
          else{
            echo 'Εμφάνιση ασθενή με στοιχεία: <strong>'.$query."</strong></br>";
          }
          echo $ans;
          echo $redirect;
        ?>
      </p>
    </div>
    <?php if ($_SESSION['role']!='Ασθενής'){ ?>
    <form action="" method ="POST">
      <div class="w3-section">
        <label>ΑΜΚΑ / Επώνυμο</label>
        <input class="w3-input w3-border" type="text" name="query">
      </div>
      <button type="submit" name="searchquery" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Αναζήτηση Ασθενή</button>
    </form>
  <?php } ?>
    <form action="" method ="POST">
      <div class="w3-section">
        <label>Date</label>
        <input class="w3-input w3-border" type="date" name="appointment_date" required value="<?php echo $date;?>">
      </div>
      <button type="submit" name="searchdate" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom" >Αναζήτηση Ημερομηνία</button>
    </form>
    <table border="2" bordercolor="red" width="100%">
      <tr>
        <th>α/α</th>
        <?php 
        if ($_SESSION['role']!='Ιατρός'){
          echo '<th>Ιατρός</th>';
        }
        if ($_SESSION['role']!='Ασθενής'){
          echo '<th>Ασθενής</th>';
        }
        ?>
        <th>Ημερομηνία</th><th>Ώρα</th><th>Λόγος</th><th>Κατάσταση</th><th>Ενέργειες</th>
      </tr>
      <?php
        $count = 1;
        foreach ($appointments as $appointment) {
          echo "<tr>";
            echo "<td>".$count."</td>";
            if ($_SESSION['role']!='Ιατρός'){
              echo "<td>".$appointment['doctor_fullname']."</td>";
            }
            if ($_SESSION['role']!='Ασθενής'){
              echo "<td>".$appointment['patient_fullname']." (".$appointment['security_number'].")</td>";
            }
            
            
            echo "<td>".$appointment['appointment_date']."</td>";
            echo "<td>".$appointment['appointment_hour']."</td>";
            echo "<td>".$appointment['reason']."</td>";
            echo "<td>".$appointment['status']."</td>";
            echo "<td>";
            if ($appointment['status']=='Δημιουργημένο'){
              echo '<form action="" method="POST">';
              echo '<input type="hidden" name="appointment_id" value="'.$appointment['id'].'">';
              echo '<button type="submit" name="update" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Ενημέρωση</button>';

              echo "</form>";
            }
            echo "</td>";
          echo "</tr>";
          $count++;
        }
       ?>

    </table>
  </div>

<!-- End page content -->
</div>

<?php
	include 'end.php';
?>