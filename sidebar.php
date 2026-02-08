

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-red w3-collapse w3-top w3-large w3-padding" style="z-index:3;width:300px;font-weight:bold;" id="mySidebar"><br>
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large w3-display-topleft" style="width:100%;font-size:22px">Close Menu</a>
  <div class="w3-container">
    <h2 ><b>Διαχείριση <br>ιατρείου</b></h2>
    <h5 >
    <?php if (isset($_SESSION['email'])){
      echo $_SESSION['email']."<br>";
      echo $_SESSION['role']."<br>";
    }
    else{
      echo "Επισκέπτης";
    } 

  ?>
  </h5>
  </div>
  <div class="w3-bar-block">
    <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Αρχική</a> 
    <?php if (isset($_SESSION['email']) && ($_SESSION['role']=='Ιατρός' || $_SESSION['role']=='Γραμματέας')){ ?>
    <a href="patientsadmin.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Διαχείριση Ασθενών</a> 
    <?php } ?>
    <?php if (isset($_SESSION['email']) && $_SESSION['role']=='Ασθενής'){ ?>
    <a href="patientrecord.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Πληροφορίες Ασθενή</a> 
    <?php } ?>
    <?php if (isset($_SESSION['email'])){ ?>
      <a href="appointmentsadmin.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Διαχείριση Ραντεβού</a> 
      <a href="myappointments.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Τα ραντεβού μου</a> 
    <?php } ?>
    <?php if (isset($_SESSION['email']) && $_SESSION['role']=='Ιατρός') { ?>
    <a href="slots.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Διαθεσιμότητα</a> 
    <?php } ?>
    <?php if (isset($_SESSION['email'])) { ?>
    <a href="logout.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Έξοδος</a>
  <?php } ?>
    <?php if (!isset($_SESSION['email'])) { ?>
    <a href="login.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Είσοδος</a>
    <a href="register.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Εγγραφή</a>
  <?php } ?>
  </div>
</nav>


