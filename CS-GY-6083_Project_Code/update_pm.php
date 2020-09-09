<?php
session_start();
include "connectdb.php";
//check to see if logged in, otherwise send to login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
$pm_role = 1;
//assign team member to PL position
if($stmt = $mysqli->prepare('SELECT user_id FROM user WHERE displayname = ?')){
    $stmt->bind_param('s',$_POST['displayname']);
    $stmt->execute();
    $stmt->bind_result($user_id);
    if ($stmt->fetch()){
        $stmt->close();
        //check if user already has a role for the project
        if($stmt=$mysqli->prepare('SELECT role_id FROM project_role WHERE project_id = ? AND user_id =?')){
            $stmt->bind_param('ii',$_SESSION['project_id'],$user_id);
            $stmt->execute();
            $stmt->store_result();
            //if role already assigned (ie. team member), delete and add new line as PL
            if ($stmt->num_rows > 0) {
                $stmt->close();
                $stmt = $mysqli->prepare('DELETE FROM project_role WHERE project_id =? AND user_id=?');
                $stmt->bind_param('ii',$_SESSION['project_id'],$user_id);
                $stmt->execute();
                $stmt->close();

                $stmt = $mysqli->prepare('INSERT INTO project_role (project_id, user_id, role_id) VALUES (?,?,?)');
                $stmt->bind_param('iii',$_SESSION['project_id'],$user_id, $pm_role);
                $stmt->execute();
                $stmt->close();
            }
            else{
                $stmt = $mysqli->prepare('INSERT INTO project_role (project_id, user_id, role_id) VALUES (?,?,?)');
                $stmt->bind_param('iii',$_SESSION['project_id'],$user_id, $pm_role);
                $stmt->execute();
                $stmt->close();

            }
        }

    }
}
header('Location: issues.php');;
?>