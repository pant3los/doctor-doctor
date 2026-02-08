<?php
	include 'start.php';
  $ans = "";
  if (isset($_POST['email'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    
    if (getCountUsersEmail($conn, $email)==0){
      $ans = 'Δεν υπάρχει χρήστης με email '.$email;
    }
    else{
      
      if (getCountUsersEmailPassword($conn,$email,$password)==0){
        $ans = 'Λάθος κωδικός για τον χρήστη με email '.$email;

      }
      else{
        
          $user = getUserInfo($conn,$email);
          $_SESSION['user_id'] = $user['user_id'];
          $_SESSION['email'] = $user['email'];
          $_SESSION['role'] = $user['role'];
          header('Location: index.php?redirect=loggedin');
          exit;
        }
    }
    
    
  }

    
    
  
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

 
  <!-- Login form -->
  <div class="w3-container" id="contact" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Είσοδος.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <?php 
    if ($ans==""){
    ?>
    <p>Μπορείτε να συνδεθείτε ως ασθενής, γραμματέας ώς γιατρός</p>
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
      
      <button type="submit" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom">Είσοδος</button>
    </form>  
  </div>

<!-- End page content -->
</div>

<?php
	include 'end.php';
?>