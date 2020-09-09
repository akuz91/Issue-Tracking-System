<?php
session_start();
include "connectdb.php";
//check to see if logged in, otherwise send to login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//assign the selected assignee in issue_role table
if($stmt = $mysqli->prepare('SELECT user_id FROM user WHERE displayname = ?')){
    $stmt->bind_param('s',$_POST['displayname']);
    $stmt->execute();
    $stmt->bind_result($user_id);
    if ($stmt->fetch()){
        $stmt->close();
        $assignee_role = 4;
        if ($stmt = $mysqli->prepare('INSERT INTO issue_role (issue_id, user_id, role_id) VALUES (?,?,?)')){
        $stmt->bind_param('iii',$_SESSION['issue_id'],$user_id,$assignee_role);
        $stmt->execute();
        $stmt->close();
        }
    }
}
header('Location: issue_history.php');;
?>