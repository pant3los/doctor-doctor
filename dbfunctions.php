<?php      
    // Επιστροφή στοιχείων patient από patient_id, -1 αν δεν το βρει
    function getUserFromPatient($conn, $patient_id){
        $sql = "SELECT p.*, u.email, u.fullname
                FROM patient p
                INNER JOIN user u ON p.user_id = u.id
                WHERE p.id = '$patient_id'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
            
        }
        return -1;
    } 
    // Επιστροφή στοιχείων patient από user_id, -1 αν δεν βρει ασθενή με user_id
    function getPatientFromUser($conn, $user_id){
        $sql = "SELECT * FROM patient
                WHERE user_id ='$user_id'";
        $result = mysqli_query($conn, $sql);

        if ($result){
            $patient = array();
            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $patient['patient_id'] = $row['id'];
                    $patient['security_number'] = $row['security_number'];
                    return $patient;
                }
            }
        }
        return -1;
    }
    // Επιστροφή user_id από doctor_id, -1 αν δεν το βρει
    function getUserIdFromDoctor($conn, $doctor_id){

    }
    // Επιστροφή στοιχείων doctor από user_id, -1 αν δεν βρει γιατρό με user_id
    function getDoctorFromUser($conn, $user_id){
        $sql = "SELECT * FROM doctor
                WHERE user_id ='$user_id'";
        $result = mysqli_query($conn, $sql);

        if ($result){
            $doctor = array();
            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $doctor['doctor_id'] = $row['id'];
                    $doctor['specialization'] = $row['specialization'];
                    return $doctor;
                }
            }
        }
        return -1;
    }
    // ενημέρωση στοιχείων ασθενή
    function updatePatient($conn,$patient_id,$fullname,$security_number){
        $user = getUserFromPatient($conn, $patient_id);
        $sql = "UPDATE patient 
        SET security_number = '$security_number' 
        WHERE id = '$patient_id'"; 
        $result = mysqli_query($conn, $sql);
        $user_id = $user['user_id'];
        $sql = "UPDATE user 
        SET fullname = '$fullname' 
        WHERE id = '$user_id'"; 
        $result = mysqli_query($conn, $sql);
    } 

    // Επιστροφή στοιχείων όλων των γιατρών
    function getAllDoctors($conn) {
        $sql = "SELECT d.*, u.email, u.fullname
                FROM doctor d
                INNER JOIN user u ON d.user_id = u.id";
        $result = mysqli_query($conn, $sql);
        $doctors = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $doctors[] = $row;
            }
        }
        return $doctors;
    }
    // Επιστροφή στοιχείων όλων των ασθενών
    function getAllPatients($conn) {
        $sql = "SELECT p.*, u.email, u.fullname
                FROM patient p
                INNER JOIN user u ON p.user_id = u.id";
        $result = mysqli_query($conn, $sql);
        $patients = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $patients[] = $row;
            }
        }
        return $patients;
    }
    // Επιστροφή στοιχείων όλων των ασθενών με ΑΜΚΑ ή επώνυμο στο query
    function getAllPatientsQuery($conn,$query){
        $sql = "SELECT p.*, u.email, u.fullname
                FROM patient p
                INNER JOIN user u 
                ON p.user_id = u.id
                WHERE u.fullname like '%$query%'
                OR p.security_number like '%$query%'";
        $result = mysqli_query($conn, $sql);
        $patients = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $patients[] = $row;
            }
        }
        return $patients;
    }
    // Διαγραφή ασθενή με id $patient_id. Αν η διαγρφή είναι επιτυχής 1 αλλιώς επιστροφή -1
    function deletePatient($conn,$patient_id){
        $res = 1;
        // Έλεγχος αν υπάρχει ιστορικό
        $sql = "SELECT COUNT(*) AS recres
                FROM record 
                WHERE patient_id ='$patient_id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row['recres']>0){
                $res=-1;
            }
        }
        // Έλεγχος αν υπάρχει ραντεβού
        $sql = "SELECT COUNT(*) AS apres
                FROM appointment 
                WHERE patient_id ='$patient_id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row['apres']>0){
                $res=-1;
            }
        }


        if ($res==1){
            $user = getUserFromPatient($conn, $patient_id);
            $user_id = $user['user_id'];
            $sql = "DELETE FROM patient 
                    WHERE id = '$patient_id'";
            mysqli_query($conn, $sql);
            $sql = "DELETE FROM user 
                    WHERE id = '$user_id'";
            mysqli_query($conn, $sql);

        }
        return $res;
    }
    // Διαγραφή εγγραφής ασθενούς
    function deleteRecord($conn,$record_id){
        $sql = "DELETE FROM record 
                    WHERE id = '$record_id'";
            mysqli_query($conn, $sql);
    }
    // Επιστροφή αριθμού χρηστών με email
    function getCountUsersEmail($conn, $email){
        
        $sql = "SELECT COUNT(*) AS res
                FROM user 
                WHERE email ='$email'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['res'];
        }
        return -1;
    }
    // Επιστροφή αριθμού χρηστών με email και password
    function getCountUsersEmailPassword($conn, $email, $password){
        $sql = "SELECT COUNT(*) AS res 
                FROM user 
                WHERE email ='$email' 
                AND password = '$password'";
      $result = mysqli_query($conn,$sql);
      if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['res'];
      }
      return -1;
    }
    // Επιστροφή στοιχείων χρήστη με email
    function getUserInfo($conn,$email){
        $sql = "SELECT * FROM user
                WHERE email ='$email'";
        $result = mysqli_query($conn, $sql);
        if ($result){
            $user = array();
            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $user['user_id'] = $row['id'];
                    $user['fullname'] = $row['fullname'];

                    $user['email'] = $row['email'];
                    $user['role'] = $row['role'];
                    if ($user['role']=='Ασθενής'){
                        $patient = getPatientFromUser($conn, $user['user_id']);
                        $user['patient_id'] = $patient['patient_id'];
                        $user['security_number'] = $patient['security_number'];
                    }
                    else if ($user['role']=='Ιατρός'){
                        $doctor = getDoctorFromUser($conn, $user['user_id']);
                        $user['doctor_id'] = $doctor['doctor_id'];
                        $user['specialization'] = $doctor['specialization'];
                    }
                }
                return $user;
            }
        }
        return -1;
    }
    // Επιστροφή id καταχώρισης slot σε πίνακα αν υπάρχει, διαφορετικά -1
    function getSlot($conn, $doctor_id, $date, $hour){
        $datetime = $date.' '.$hour; // Συγχώνευση της ημερομηνίας και της ώρας
        $sql = "SELECT id FROM slot 
                WHERE doctor_id = '$doctor_id' 
                AND timeslot = '$datetime'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['id']; // Επιστρέφει το id αν η εγγραφή υπάρχει
        } 
        return -1; 
    }
    // Επιστροφή πίνακα με διαθεσιμότητα γιατρού για κάποια ημερομηνία
    function getSlots($conn, $doctor_id, $date){
        $sql = "SELECT timeslot 
                FROM slot 
                WHERE DATE(timeslot) = '$date' 
                AND doctor_id = '$doctor_id'";
        $result = mysqli_query($conn, $sql);

        $hours = array();

        // Αποθήκευση των ωρών στον πίνακα
        while($row = mysqli_fetch_assoc($result)) {
            $hours[] = date('H:i', strtotime($row['timeslot']));

        }
        return $hours;
    }
    // Ανάθεση διαθεσιμότητας γιατρού για κάποια ημερομηνία
    // Ο πίνακας $hours περιλαμβάνει όλες τις διαθέσιμες ώρες και γίνεται έλεγχος 
    // αν για κάθε ώρα υπάρχει καταχώριση στην βάση. 
    function setSlots($conn, $doctor_id, $date, $hours){
        $start_time = strtotime('09:00');
        $interval = 30 * 60; // 30 λεπτά σε δευτερόλεπτα
        $time = $start_time;
        for($i=0;$i<24;$i+=1){
            $hour = date('H:i', $time);
            // Αν η καταχώριση υπάρχει στη βάση αλλά όχι στον πίνακα διαγράφεται
            if (!in_array($hour, $hours)){
                $slot_id = getSlot($conn, $doctor_id, $date, $hour);
                if ($slot_id!=-1){  // Υπάρχει στη βάση
                    deleteSlot($conn, $slot_id);
                }
            }
            //  Αν η καταχώριση υπάρχει στον πίνακα αλλά όχι στην βάση γίνεται προσθήκη
            else{
                $slot_id = getSlot($conn, $doctor_id, $date, $hour);
                if ($slot_id==-1){  // Δεν υπάρχει στη βάση
                    insertSlot($conn, $doctor_id, $date.' '.$hour);
                }
            }
            $time += $interval;
        }
    }
    // Διαγραφή slot με id slot_id
    function deleteSlot($conn, $slot_id) {
        $sql = "DELETE FROM slot 
        WHERE id = $slot_id";
        mysqli_query($conn, $sql);
    }
    // Εισαγωγή slot
    function insertSlot($conn, $doctor_id, $timeslot) {
        $sql = "INSERT INTO slot (doctor_id, timeslot) 
                VALUES ('$doctor_id', '$timeslot')";
        mysqli_query($conn, $sql);
    }
    // Επιστρέφει αν ένας γιατρός είναι διαθέσιμος. Για να είναι διαθέσιμος θα πρέπει και να υπάρχει slot και να μην έχει ραντεβού εκείνη την ώρα. -1 αν δεν είναι διαθέσιμος, 1 αν είναι
    function isAvailable($conn, $doctor_id,$date, $hour){
        if (getSlot($conn, $doctor_id, $date, $hour)==-1){
            return -1;
        }
        if (getAppointment($conn, $doctor_id, $date, $hour)!=-1){
            return -1;
        }
        return 1;

    }
    // αναθέτει ραντεβού γιατρού σε ασθενή συγκεκριμένη ώρα και μέρα
    function setAppointment($conn, $doctor_id, $patient_id, $reason, $date, $hour){

        $sql = "INSERT INTO appointment (appointment_date, appointment_hour, reason, doctor_id, patient_id, register_date) 
                VALUES ('$date','$hour','$reason', '$doctor_id', '$patient_id', now())";
        mysqli_query($conn, $sql);
    }
    // Ενημερώνει κάποιο ραντεβού θέτοντας νέα ημέρα και ώρα. 
    function updateAppointment($conn, $appointment_id,$appointment_date, $appointment_hour){
        $sql = "UPDATE appointment 
        SET appointment_date = '$appointment_date',
        appointment_hour = '$appointment_hour'
        WHERE appointment.id = '$appointment_id'"; 
        mysqli_query($conn, $sql);
    }


    // Επιστροφή id καταχώρισης ραντεβού αν υπάρχει αυτήν την ώρα για τον γιατρό, διαφορετικά -1
    function getAppointment($conn, $doctor_id, $date, $hour){
        $datetime = $date.' '.$hour; // Συγχώνευση της ημερομηνίας και της ώρας
        $sql = "SELECT id FROM appointment 
                WHERE doctor_id = '$doctor_id' 
                AND appointment_date = '$date'
                AND appointment_hour = '$hour'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['id']; // Επιστρέφει το id αν η εγγραφή υπάρχει
        } 
        return -1; 
    }
    // Επιστροφή στοιχείων ραντεβού με $id αν υπάρχει, αλλιώς -1
    function getAppointmentId($conn,$id){
        $sql = "SELECT a.*,p.security_number,up.fullname as patient_fullname, 
                ud.fullname as doctor_fullname,d.specialization
                FROM appointment a
                INNER JOIN doctor d 
                ON d.id = a.doctor_id
                INNER JOIN patient p 
                ON p.id = a.patient_id
                INNER JOIN user up 
                ON p.user_id = up.id
                INNER JOIN user ud 
                ON d.user_id = ud.id
                WHERE a.id = '$id'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row;
            }
        }
        return -1;
    }
    // Επιστροφή στοιχείων όλων των ραντεβού
    function getAllAppointments($conn,$date) {
        $sql = "SELECT a.*,p.security_number,up.fullname as patient_fullname, 
                ud.fullname as doctor_fullname,d.specialization
                FROM appointment a
                INNER JOIN doctor d 
                ON d.id = a.doctor_id
                INNER JOIN patient p 
                ON p.id = a.patient_id
                INNER JOIN user up 
                ON p.user_id = up.id
                INNER JOIN user ud 
                ON d.user_id = ud.id
                WHERE a.appointment_date = '$date'";
        $result = mysqli_query($conn, $sql);
        $appointments = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }
    // Επιστροφή στοιχείων όλων των ραντεβού ενός γιατρού
    function getAllAppointmentsDoctor($conn,$doctor_id,$date) {
        $sql = "SELECT a.*,p.security_number,up.fullname as patient_fullname, 
                ud.fullname as doctor_fullname, d.specialization
                FROM appointment a
                INNER JOIN doctor d
                ON d.id = a.doctor_id
                INNER JOIN patient p 
                ON p.id = a.patient_id
                INNER JOIN user up 
                ON p.user_id = up.id
                INNER JOIN user ud 
                ON d.user_id = ud.id
                WHERE d.id = '$doctor_id'
                AND a.appointment_date = '$date'";
        $result = mysqli_query($conn, $sql);
        $appointments = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }
    // Επιστροφή στοιχείων όλων των ραντεβού ενός ασθενή
    function getAllAppointmentsPatient($conn,$patient_id,$date) {
        $sql = "SELECT a.*,p.security_number,up.fullname as patient_fullname, 
                ud.fullname as doctor_fullname, d.specialization
                FROM appointment a
                INNER JOIN doctor d
                ON d.id = a.doctor_id
                INNER JOIN patient p 
                ON p.id = a.patient_id
                INNER JOIN user up 
                ON p.user_id = up.id
                INNER JOIN user ud 
                ON d.user_id = ud.id
                WHERE p.id = '$patient_id'
                AND a.appointment_date = '$date'";
        $result = mysqli_query($conn, $sql);
        $appointments = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }
    // Όλα τα ραντεβού των ασθενών που ταιριάζουν με το query
    function getAllAppointmentsQuery($conn,$patientquery){
        $sql = "SELECT a.*,p.security_number,up.fullname as patient_fullname, 
                ud.fullname as doctor_fullname, d.specialization
                FROM appointment a
                INNER JOIN doctor d
                ON d.id = a.doctor_id
                INNER JOIN patient p 
                ON p.id = a.patient_id
                INNER JOIN user up 
                ON p.user_id = up.id
                INNER JOIN user ud 
                ON d.user_id = ud.id
                WHERE up.fullname like '%$patientquery%'
                OR p.security_number like '%$patientquery%'";
        $result = mysqli_query($conn, $sql);
        $appointments = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }
    // Όλα τα ραντεβού των ασθενών που ταιριάζουν με το query από τον γιατρό με doctor_id
    function getAllAppointmentsDoctorQuery($conn,$patientquery,$doctor_id){
        $sql = "SELECT a.*,p.security_number,up.fullname as patient_fullname, 
                ud.fullname as doctor_fullname, d.specialization
                FROM appointment a
                INNER JOIN doctor d
                ON d.id = a.doctor_id
                INNER JOIN patient p 
                ON p.id = a.patient_id
                INNER JOIN user up 
                ON p.user_id = up.id
                INNER JOIN user ud 
                ON d.user_id = ud.id
                WHERE (up.fullname like '%$patientquery%'
                OR p.security_number like '%$patientquery%')
                AND d.id = '$doctor_id'";
        $result = mysqli_query($conn, $sql);
        $appointments = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }
    // Ακύρωση ραντεβού με id appointment_id
    function cancelAppointment($conn, $appointment_id) {
        $sql = "UPDATE appointment 
        SET status = 'Ακυρωμένο' 
        WHERE appointment.id = '$appointment_id'"; 
        mysqli_query($conn, $sql);
    }
    // Ολοκλήρωση ραντεβού με id appointment_id
    function completeAppointment($conn, $appointment_id) {
        $sql = "UPDATE appointment 
        SET status = 'Ολοκληρωμένο' 
        WHERE appointment.id = '$appointment_id'"; 
        mysqli_query($conn, $sql);
    }
    // Καταγράφει εγγραφή ιστορικού ασθενούς
    function setRecord($conn, $doctor_id, $patient_id, $health_problems, $treatment){

        $sql = "INSERT INTO record (doctor_id, patient_id, health_problems, treatment, register_date)
                VALUES ('$doctor_id','$patient_id','$health_problems', '$treatment', now())";
        mysqli_query($conn, $sql);
    }

    // Επιστροφή στοιχείων όλων των εγγραφών ενός ασθενούς
    function getPatientsRecords($conn,$patient_id) {
        $sql = "SELECT r.*, ud.fullname as doctor_fullname, d.specialization
                FROM record r
                INNER JOIN doctor d
                ON d.id = r.doctor_id
                INNER JOIN user ud 
                ON d.user_id = ud.id
                WHERE patient_id = '$patient_id'
                ORDER BY r.register_date DESC";
        $result = mysqli_query($conn, $sql);
        $records = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
            return $records;
        }
        return -1;
        
    }
    // Επιστροφή στοιχείων όλων των εγγραφών ενός ασθενούς μία ημερομηνία
    function getPatientsRecordsDate($conn,$patient_id,$register_date) {
        $sql = "SELECT r.*, ud.fullname as doctor_fullname, d.specialization
                FROM record r
                INNER JOIN doctor d
                ON d.id = r.doctor_id
                INNER JOIN user ud 
                ON d.user_id = ud.id
                WHERE patient_id = '$patient_id'
                AND register_date='$register_date'
                ORDER BY r.register_date DESC";
        $result = mysqli_query($conn, $sql);
        $records = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
            return $records;
        }
        return -1;
        
    }
    // Επιστροφή στοιχείων όλων των εγγραφών ενός ασθενούς με κάποιο πρόβλημα υγείας
    function getPatientsRecordsHealth($conn,$patient_id,$health_problems) {
        $sql = "SELECT r.*, ud.fullname as doctor_fullname, d.specialization
                FROM record r
                INNER JOIN doctor d
                ON d.id = r.doctor_id
                INNER JOIN user ud 
                ON d.user_id = ud.id
                WHERE patient_id = '$patient_id'
                AND health_problems like '%$health_problems%'
                ORDER BY r.register_date DESC";
        $result = mysqli_query($conn, $sql);
        $records = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
            return $records;
        }
        return -1;
    }
?>

