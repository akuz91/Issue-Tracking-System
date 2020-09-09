<?php
session_start();
include "connectdb.php";
//check to see if logged in, otherwise send to login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//issue descr validation, any letters or numbers
if (preg_match('/[A-Za-z0-9]+/', $_POST['update_descr']) == 0) {
    exit('Issue description is not valid');
}

// enter issue_title & issue_descr from user into database as new project
if($stmt = $mysqli->prepare('SELECT state_id FROM state WHERE state_name = ? AND project_id = ?')){
    $stmt->bind_param('si',$_POST['state'], $_SESSION['project_id']);
    $stmt->execute();
    $stmt->bind_result($state_id);
    if ($stmt->fetch()){
        $stmt->close();
        if ($stmt = $mysqli->prepare('INSERT INTO issue_tracking (issue_id, status, update_descr, updated_by) VALUES (?,?,?,?)')) {
            $stmt->bind_param('iisi',$_SESSION['issue_id'],$state_id, $_POST['update_descr'], $_SESSION['id']);
            $stmt->execute();
            $stmt->close();
        } else {
            //something is wrong with stmt
            echo 'Could not prepare statement!';
        }
    

    }
}   
header('Location: issue_history.php');;   
?>

