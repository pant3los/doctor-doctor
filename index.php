<?php
	include 'start.php';
	$redirect = "Καλωσήλθατε";
	if (isset($_GET['redirect'])){
		if ($_GET['redirect']=='loggedin'){
			$redirect = 'Επιτυχής σύνδεση χρήστη '.$_SESSION['email'];
		}
		else if ($_GET['redirect']=='registered'){
			$redirect = 'Επιτυχής εγγραφή '.$_SESSION['role'].' '.$_SESSION['email'];
		}
		else if ($_GET['redirect']=='logout'){
			$redirect = 'Επιτυχής αποσύνδεση';
		}
		else if ($_GET['redirect']=='unauthorized'){
			$redirect = 'Μή εξουσιοδοτημένη σελίδα';
		}
		else if ($_GET['redirect']=='nopatient'){
			$redirect = 'Πρέπει να επιλεγεί ασθενής για να δείτε τις πληροφορίες του';
		}
    else if ($_GET['redirect']=='noappointment'){
      $redirect = 'Πρέπει να επιλεγεί ραντεβού για να δείτε τις πληροφορίες του';
    }
    else if ($_GET['redirect']=='noidappointment'){
      $redirect = 'Δεν υπάρχει ραντεβού με το επιλεγμένο id για να δείτε τις πληροφορίες του';
    }
    else if ($_GET['redirect']=='wrongidappointment'){
      $redirect = 'Το ραντεβού που επιλέξατε δεν αφορά εσας';
    }
    else if ($_GET['redirect']=='wrongidpatient'){
      $redirect = 'Ο ασθενής που επιλέξατε δεν βρέθηκε';
    }

			
	}
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

  <!-- Header -->
  <div class="w3-container" style="margin-top:80px" id="showcase">
    <h1 class="w3-jumbo"><b>Διαχείριση ιατρείου</b></h1>
    <h1 class="w3-xxxlarge w3-text-red"><b>
    	<?php echo $redirect;?>
    </b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
  </div>
  
  <!-- Photo grid (modal) -->
  <div class="w3-row-padding">
    <center>
      <img src="images/1.jpg" style="width:30%" onclick="onClick(this)" alt="Concrete meets bricks">
      <img src="images/2.jpg" style="width:30%" onclick="onClick(this)" alt="Light, white and tight scandinavian design">
      <img src="images/3.jpg" style="width:30%" onclick="onClick(this)" alt="White walls with designer chairs">
    </center>
  </div>

  <!-- Modal for full size images on click-->
  <div id="modal01" class="w3-modal w3-black" style="padding-top:0" onclick="this.style.display='none'">
    <span class="w3-button w3-black w3-xxlarge w3-display-topright">×</span>
    <div class="w3-modal-content w3-animate-zoom w3-center w3-transparent w3-padding-64">
      <img id="img01" class="w3-image">
      <p id="caption"></p>
    </div>
  </div>



<!-- End page content -->
</div>

<?php
	include 'end.php';
?>