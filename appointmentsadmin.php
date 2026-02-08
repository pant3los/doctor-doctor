<?php
	include 'start.php';
  $ans = "";
  // Αν δεν είναι διαπιστευμένος, επιστροφή στην αρχική
  if (!(isset($_SESSION['email']))) {
    header('Location: index.php?redirect=unauthorized');
    exit;
  }
  $doctors = getAllDoctors($conn);
  $patients = getAllPatients($conn);
  
  $doctor_id = $doctors[0]['id'];
  if ($_SESSION['role']=='Ιατρός'){
    $doctor_id = $user['doctor_id'];
  }
  $hours = array();
  if (isset($_POST['select'])){
    $date = $_POST['date'];
    $ans = "Επιλογή Ιατρού - Ημερομηνίας";
    if ($_SESSION['role']!='Ιατρός'){
      $doctor_id = $_POST['doctor_id'];
    }
  }
  else if (isset($_POST['setappointment'])){
    $date = $_POST['date'];
    $hour = $_POST['hour'];
    $doctor_id = $_POST['doctor_id'];
    $patient_id = $_POST['patient_id'];
    $reason = $_POST['reason'];
    setAppointment($conn, $doctor_id, $patient_id, $reason, $date, $hour);
    $ans = "Ανάθεση ραντεβού";
  }
  else{ // Σημερινή ημερομηνία
    $date = date('Y-m-d');

  }
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">
  <div class="w3-container" id="appointment" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Διαχείριση ραντεβού.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p><?php echo $ans; ?></p>
  </div>

 <form action="" method="POST">
      <div class="w3-section">
        <label>Ιατρός</label>
        <?php 
          if ($_SESSION['role']=='Ιατρός'){ ?>
            <input type="hidden" name = "doctor_id" value="<?php echo $doctor_id;?>">
            <select name="doctor_id" disabled>
        <?php
          }
          else{
            ?>
            <select name="doctor_id">
          <?php
          }
          foreach ($doctors as $doctor) {
            if ($doctor_id==$doctor['id']){

              echo '<option value="'.$doctor['id'].'" selected>' .$doctor['fullname'].' '.$doctor['specialization'].'</option>';
            }
            else{
              echo '<option value="'.$doctor['id'].'" >' .$doctor['fullname'].' '.$doctor['specialization'].'</option>';
            }
          }
        ?>
        </select>
        </div>
        <div class="w3-section">
        <label>Ημερομηνία</label>
        <input class="w3-input w3-border" type="date" name="date" value="<?php echo $date;?>" required>
      </div>
        <button type="submit" name="select" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Επιλογή</button>
      <div class="w3-section">
        <label>Ώρες</label>
        <table border="2" bordercolor="red" width="80%">
          <tr>
        <?php
          $start_time = strtotime('09:00');
          //$end_time = strtotime('21:00');
          $interval = 30 * 60; // 30 λεπτά σε δευτερόλεπτα
          $count = 0;
          $time = $start_time;

          for ($i=0;$i<3;$i++) {
              echo "<td style='text-align: center;'>";
              for ($j=0;$j<8;$j++) {
                $hour = date('H:i', $time);
                if (getSlot($conn, $doctor_id, $date, $hour)!=-1){
                  if (getAppointment($conn, $doctor_id, $date, $hour)==-1){
                    echo '<span style="color:black;">';
                    echo '<input type="radio" name="hour" value="'.$hour.'">';
                  }
                  else{
                    echo '<span style="color:blue;">';
                    echo '<input type="radio" name="hour" value="'.$hour.'" disabled>';
                  }
                }
                else{
                  echo '<span style="color:red;">';
                  echo '<input type="radio" name="hour" value="'.$hour.'" disabled>';
                  
                }
                echo $hour.' - '.date('H:i', $time+$interval);
                echo '</span>';
                echo '<br>';
                
                $time += $interval;
              }
              echo "</td>";
          }
          ?>
        </tr>
        </table>
      </div>
      <div class="w3-section">
        <label>Ασθενής

        </label>
        <?php 
          if ($_SESSION['role']=='Ασθενής'){ ?>
            <input type="hidden" name = "patient_id" value="<?php echo $patient_id;?>">;
            <select name="patient_id" disabled>
        <?php
          }
          else{
            ?>
            <select name="patient_id">
          <?php
          }
          foreach ($patients as $patient) {
            if ($patient_id==$patient['id']){
              echo '<option value="'.$patient['id'].'" selected>' .$patient['fullname'].' '.$patient['security_number'].' '.$patient['id'].'</option>';
            }
            else{
              echo '<option value="'.$patient['id'].'" >' .$patient['fullname'].' '.$patient['security_number'].'</option>';
            }
          }
        ?>
        </select>
        </div>
        <div class="w3-section">
          <label>Λόγος Ραντεβού</label>
          <input class="w3-input w3-border" type="text" name="reason">
        </div>
      <button type="submit" name="setappointment" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Εισαγωγή Ραντεβού</button>
    </form> 
  

<!-- End page content -->
</div>

<?php
	include 'end.php';
?>