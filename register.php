<?php
	include 'start.php';
  $ans = "";
  if (isset($_POST['email'])){
    $email = $_POST['email'];

    if (getCountUsersEmail($conn, $email)>0) {
        $ans = 'Υπάρχει ήδη χρήστης με email '.$email;
    }
    else{
      $password = $_POST['password'];
      $fullname = $_POST['fullname'];
      $idcard = $_POST['idcard'];
      $role = $_POST['role'];

      if ($role == 'Ασθενής'){
        $security_number = $_POST['security_number'];
        if ($security_number==""){
          $ans = 'Οι ασθενείς πρέπει υποχρεωτικά να έχουν ΑΜΚΑ';
        }
        else{
          $sql = "INSERT INTO user (email, password, fullname, idcard, role) 
              VALUES ('$email', '$password', '$fullname', '$idcard', '$role')";
          if (mysqli_query($conn, $sql)) {
            $user_id = mysqli_insert_id($conn);
            $sql = "INSERT INTO patient (user_id,  security_number, register_date) 
              VALUES ('$user_id', '$security_number', now())";
            if (mysqli_query($conn, $sql)) {
              $_SESSION["id"] = $user_id;
              $_SESSION["email"] = $email;
              $_SESSION["role"] = $role;
              header('Location: index.php?redirect=registered');
              exit;
              
            }
            else{
              $ans = 'Πρόβλημα στην εισαγωγή χρήστη';
            }
          }
        }
      }
      else if ($role=='Ιατρός'){
        $sql = "INSERT INTO user (email, password, fullname, idcard, role) 
              VALUES ('$email', '$password', '$fullname', '$idcard', '$role')";
          if (mysqli_query($conn, $sql)) {
            $user_id = mysqli_insert_id($conn);
            $specialization = $_POST['specialization'];
            $sql = "INSERT INTO doctor (user_id, specialization) 
              VALUES ('$user_id', '$specialization')";
            if (mysqli_query($conn, $sql)) {
              $_SESSION["id"] = $user_id;
              $_SESSION["email"] = $email;
              $_SESSION["role"] = $role;
              header('Location: index.php?redirect=registered');
              exit;
              
            }
            else{
              $ans = 'Πρόβλημα στην εισαγωγή χρήστη';
            }
          }
      }
      else{
        $sql = "INSERT INTO user (email, password, fullname, idcard, role) 
              VALUES ('$email', '$password', '$fullname', '$idcard', '$role')";
          if (mysqli_query($conn, $sql)) {
            $user_id = mysqli_insert_id($conn);

            $_SESSION["id"] = $user_id;
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;
            header('Location: index.php?redirect=registered');
            exit;
          }
          else{
            $ans = 'Πρόβλημα στην εισαγωγή χρήστη';
          }

      }


    }
  }
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

 
  <!-- Login form -->
  <div class="w3-container" id="contact" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Εγγραφή.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <?php 
    if ($ans==""){
    ?>
    <p>Μπορείτε να εγγραφείτε ως ασθενής, γραμματέας ώς γιατρός</p>
    <?php 
    } 
    else{ 
    ?>
      <p><?php echo $ans;?></p>
    <?php } ?>
    <form action="" method="POST">
      <div class="w3-section">
        <label>Email</label>
        <input class="w3-input w3-border" type="text" name="email" required>
      </div>
      <div class="w3-section">
        <label>Κωδικός</label>
        <input class="w3-input w3-border" type="password" name="password" required>
      </div>
      <div class="w3-section">
        <label>Ονοματεπώνυμο</label>
        <input class="w3-input w3-border" type="text" name="fullname" required>
      </div>
      <div class="w3-section">
        <label>Αρ. Ταυτότητας</label>
        <input class="w3-input w3-border" type="text" name="idcard" required>
      </div>
      <div class="w3-section">
        <label>Ρόλος</label>
        <select name="role">
          <option value="Ασθενής" selected>Ασθενής</option>
          <option value="Ιατρός">Ιατρός</option>
          <option value="Γραμματέας">Γραμματέας</option>
        </select>
      </div>
      <div id="asthenis">
        <div class="w3-section">
        <label>ΑΜΚΑ</label>
        <input class="w3-input w3-border" type="text" name="security_number">
      </div>
      </div>
      <div id="giatros">
        <div class="w3-section">
        <label>Ειδικότητα</label>
        <select name="specialization">
          <option value="Γενική Ιατρική">Γενική Ιατρική</option>
          <option value="Παθολόγος">Παθολόγος</option>
          <option value="Χειρούργος">Χειρούργος</option>
          <option value="Ορθοπεδικός">Ορθοπεδικός</option>
          <option value="Παιδίατρος">Παιδίατρος</option>
          <option value="Γυναικολόγος">Γυναικολόγος</option>
          <option value="Νευρολόγος">Νευρολόγος</option>
          <option value="Καρδιολόγος">Καρδιολόγος</option>
        </select>
      </div>
      </div>
      <button type="submit" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Εγγραφή</button>
    </form>  
  </div>

<!-- End page content -->
</div>
<!-- Role display script -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Κρύψτε όλα τα divs με τα id asthenis, giatros, grammateas
    document.getElementById('asthenis').style.display = 'block';
    document.getElementById('giatros').style.display = 'none';
    
    // Εάν αλλάξει η επιλογή στο πεδίο επιλογής
    document.querySelector('select[name="role"]').addEventListener('change', function() {
      var selectedOption = this.value; // Αποθηκεύστε την επιλεγμένη επιλογή
      
      // Κρύψτε όλα τα divs
      document.getElementById('asthenis').style.display = 'none';
      document.getElementById('giatros').style.display = 'none';
      
      // Εμφανίστε το αντίστοιχο div ανάλογα με την επιλογή
      if (selectedOption === 'Ασθενής') {
        document.getElementById('asthenis').style.display = 'block';
      } else if (selectedOption === 'Ιατρός') {
        document.getElementById('giatros').style.display = 'block';
      } 
      
    });
  });
</script>
<?php
	include 'end.php';
?>