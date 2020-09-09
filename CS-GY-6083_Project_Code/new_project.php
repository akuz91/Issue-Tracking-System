<?php
session_start();
include "connectdb.php";
//check to see if logged in, otherwise send to login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//project name validation, any letters or numbers
if (preg_match('/[A-Za-z0-9]+/', $_POST['project_name']) == 0) {
    exit('Project name is not valid');
}
//project descr validation, any letters or numbers
if (preg_match('/[A-Za-z0-9]+/', $_POST['project_descr']) == 0) {
    exit('Project description is not valid');
}
//INSERT NEW PROJECT INFO INTO PROJECT TABLE
// enter project_name & project_descr from user into database as new project
if ($stmt = $mysqli->prepare('INSERT INTO project (project_name, project_descr) VALUES (?,?)')) {
    $stmt->bind_param('ss',$_POST['project_name'], $_POST['project_descr']);
    $stmt->execute();
    $stmt->close();
} else {
    //something is wrong with stmt
    echo 'Could not prepare statement!';
}
//ASSIGN USER TO PROJECT MANAGER ROLE
// project_id is autoincrement, so get it by searching for newly inserted project name & descr
if ($stmt = $mysqli->prepare('SELECT project_id FROM project WHERE project_name = ? AND project_descr = ?')) {
    $stmt->bind_param('ss',$_POST['project_name'], $_POST['project_descr']);
    $stmt->execute();
    $stmt->bind_result($project_id);
    if ($stmt->fetch()) {
        $stmt->close();
        // assign whoever created new project to Project Manager Role
        $pm_role = 1;
        if ($stmt = $mysqli->prepare('INSERT INTO project_role (project_id, user_id, role_id) VALUES(?,?,?)')) {
            $stmt->bind_param('iii', $project_id, $_SESSION['id'],$pm_role);
            $stmt->execute();
            $stmt->close();
        }
    }
}

//INSERT NEW STATUS INFO INTO STATUS TABLE
$status_name = $_POST['status_name'];
//check how many entries have been filled
$status_name = array_filter($status_name);
//check there is a start AND end for each row of statuses
if (count($status_name)%2 !=0){
    exit('Please ensure both Workflow Status Start & End are filled out, otherwise leave row blank.');
}
for($i=0;$i<=count($status_name)-1;$i++){
//for each status entered, check if workflow status already entered in status table for this project
    if($stmt = $mysqli->prepare('SELECT state_id FROM state WHERE state_name = ? AND project_id = ?')){
        //bind params
        $stmt->bind_param('si',htmlspecialchars($status_name[$i],ENT_NOQUOTES, 'UTF-8'), $project_id);
        $stmt->execute();
        $stmt->store_result();
        //if not in the table yet, add it
        if ($stmt->num_rows <1){
            $stmt = $mysqli->prepare('INSERT INTO state (state_name, project_id) VALUES (?,?)');
            $stmt->bind_param('si',htmlspecialchars($status_name[$i], ENT_NOQUOTES,'UTF-8'),$project_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}
//INSERT NEW TRANSITION INFO INTO TRANSITION TABLE
//sweep through all text boxes with two counters; 1 for current state & 1 for subsequent state, increment both by 2
for($i=0, $k=1;$i<=count($status_name)-1;$i+=2,$k+=2){
    //find (1) state's corresponding state id and save as current state
    $stmt = $mysqli->prepare('SELECT state_id FROM state WHERE state_name = ? AND project_id = ?'); 
    $stmt->bind_param('si', htmlspecialchars($status_name[$i], ENT_NOQUOTES,'UTF-8'), $project_id);
    $stmt->execute();
    $stmt->bind_result($current_state);
    $stmt->fetch();
    $stmt->close();
    
    //find (2) state's corresponding state id and save as subsequent state
    $stmt = $mysqli->prepare('SELECT state_id FROM state WHERE state_name = ? AND project_id = ?'); 
    $stmt->bind_param('si', htmlspecialchars($status_name[$k], ENT_NOQUOTES,'UTF-8'), $project_id);
    $stmt->execute();
    $stmt->bind_result($subsequent_state);
    $stmt->fetch();
    $stmt->close();
    
    //insert current state & subsequent state into transition table
    $stmt = $mysqli->prepare('INSERT INTO transition (current_state, subsequent_state) VALUES (?,?)');
    $stmt->bind_param('ii',$current_state,$subsequent_state);
    $stmt->execute();
    $stmt->close();
}
header('Location: home.php');;
?>
