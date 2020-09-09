<?php
session_start();
include "connectdb.php";
//check to see if logged in, otherwise send to login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//assign new team member to the project
if($stmt = $mysqli->prepare('SELECT user_id FROM user WHERE displayname = ?')){
    $stmt->bind_param('s',$_POST['displayname']);
    $stmt->execute();
    $stmt->bind_result($user_id);
    if ($stmt->fetch()){
        $stmt->close();
        $team_role =2;
        if ($stmt = $mysqli->prepare('INSERT INTO project_role (project_id, user_id, role_id) VALUES (?,?,?)')){
        $stmt->bind_param('iii',$_SESSION['project_id'],$user_id,$team_role);
        $stmt->execute();
        $stmt->close();
        }
    }
}
header('Location: issues.php');;
?>