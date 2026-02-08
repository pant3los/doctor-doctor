<?php
	include 'start.php';
  $redirect = "";
  // Αν δεν είναι διαπιστευμένος Ιατρός, επιστροφή στην αρχική
  if (!(isset($_SESSION['email']) && $_SESSION['role']=='Ιατρός')) {
    header('Location: index.php?redirect=unauthorized');
    exit;
  }
  if (isset($_GET['redirect'])){
    if ($_GET['redirect']=='nofile'){
      $redirect = 'Πρέπει να επιλεγεί αρχείο';
    }
    else if ($_GET['redirect']=='success'){
      $redirect = 'Διαβάστηκαν '.$_GET['count'].' time slots';
    }
  }
  $doctor_id = $user['doctor_id'];

  $ans = "";
  $hours = array();
  if (isset($_POST['select'])){
    $date = $_POST['date'];
    $ans = "Επιλογή Ημερομηνίας";
  }
  else if (isset($_POST['setslots'])){
    $date = $_POST['date'];
    $hours = $_POST['hours'];
    setSlots($conn, $doctor_id, $date, $hours);
    $ans = "Ανάθεση slots σε ημερομηνία";
  }
  else{ // Σημερινή ημερομηνία
    $date = date('Y-m-d');

  }
  $hours = getSlots($conn, $user['doctor_id'], $date);
?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

 
  
  <!-- Contact -->
  <div class="w3-container" id="contact" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>
      Διαθεσιμότητα.
      
    </b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p>
      <?php echo $ans; ?>
      <?php echo $redirect ?>
    </p>
    <form action="" method="POST">
      <div class="w3-section">
        <label>Ημερομηνία</label>
        <input class="w3-input w3-border" type="date" name="date" value="<?php echo $date;?>" required>
        <button type="submit" name="select" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Επιλογή</button>
      </div>
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
                if (in_array($hour, $hours)){
                  echo '<input type="checkbox" name="hours[]" value="'.$hour.'" checked>';
                }
                else{
                  echo '<input type="checkbox" name="hours[]" value="'.$hour.'">';
                }
                echo $hour.' - '.date('H:i', $time+$interval);
                echo '<br>';
                
                $time += $interval;
              }
              echo "</td>";
          }
          ?>
        </tr>
        </table>
      </div>
      <button type="submit" name="setslots" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Διαθεσιμότητα</button>
    </form>  
    <form action="slotsfile.php" method="POST" enctype="multipart/form-data">
      <div class="w3-section">
        <label>Αρχείο</label>
        <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
        <button type="submit" name="select" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Μαζική Διαθεσιμότητα</button>
      </div>
    </form>
  </div>

<!-- End page content -->
</div>
<?php
  include 'end.php';
?>