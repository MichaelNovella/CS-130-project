<?php
if (isset($_POST['login-pressed'])) {
    require 'helperfunctions.php';
    $mailuid = $_POST['userid'];
    $pass = $_POST['passd'];
    
    if (empty($mailuid) || empty($pass)) {
        header("Location: index.php?error=emptyfield");
    exit();
    }
    else{
        $sql = "SELECT * FROM users WHERE usernamesid=? OR email_id=?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$mailuid,$mailuid);
        $stmt->execute();
        $resukj = $stmt->get_result();
        if ($row = $resukj->fetch_assoc()) {
            $passcheck = password_verify($pass,$row['pwdusers']);
            if ($passcheck == false) {
                header("Location: index.php?error=wrongpass");
            exit();
            }
            elseif($passcheck == true){
                session_start();
                $_SESSION['userid']= $row['idUser'];
                $_SESSION['usernameloggedin']= $row['usernamesid'];
                header("Location: index.php?signing=pass");
            exit();
            }
            else{
                header("Location: index.php?error=wrongpass");
            exit();
            }
        }
        else {
            header("Location: index.php?error=nouser");
            exit();
        }
    }
}
else {
    header("Location: index.php");
    exit();
}
?>