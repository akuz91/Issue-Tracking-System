<?php
session_start();
include "connectdb.php";
//check to see if logged in, otherwise send to login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//issue title validation, any letters or numbers
if (preg_match('/[A-Za-z0-9]+/', $_POST['issue_title']) == 0) {
    exit('Issue title is not valid');
}
//issue descr validation, any letters or numbers
if (preg_match('/[A-Za-z0-9]+/', $_POST['issue_descr']) == 0) {
    exit('Issue description is not valid');
}
// ENTER NEW ISSUE
if ($stmt = $mysqli->prepare('INSERT INTO issue (issue_title, issue_descr, project_id) VALUES (?,?,?)')) {
    $stmt->bind_param('ssi',$_POST['issue_title'], $_POST['issue_descr'], $_SESSION['project_id']);
    $stmt->execute();
    $stmt->close();
} else {
    //something is wrong with stmt
    echo 'Could not prepare statement!';
}
//ASSIGN USER AS REPORTER
// issue_id is autoincrement, so get it by searching for newly inserted issue_title & descr
if ($stmt = $mysqli->prepare('SELECT issue_id FROM issue WHERE issue_title = ? AND issue_descr = ?')) {
    $stmt->bind_param('ss',$_POST['issue_title'], $_POST['issue_descr']);
    $stmt->execute();
    $stmt->bind_result($issue_id);
    if ($stmt->fetch()) {
        $stmt->close();
        // assign whoever created new issue as reporter of the issue in issue_role table
        $report_role = 3;
        if ($stmt = $mysqli->prepare('INSERT INTO issue_role (issue_id, user_id, role_id) VALUES(?,?,?)')) {
            $stmt->bind_param('iii', $issue_id, $_SESSION['id'], $report_role);
            $stmt->execute();
            $stmt->close();
        }
    }
}
//ASSIGN ASSIGNEE
//assign the selected assignee in issue_role table
if($stmt = $mysqli->prepare('SELECT user_id FROM user WHERE displayname = ?')){
    $stmt->bind_param('s',$_POST['displayname']);
    $stmt->execute();
    $stmt->bind_result($user_id);
    if ($stmt->fetch()){
        $stmt->close();
        $assign_role = 4;
        if ($stmt = $mysqli->prepare('INSERT INTO issue_role (issue_id, user_id, role_id) VALUES (?,?,?)')){
        $stmt->bind_param('iii',$issue_id,$user_id,$assign_role);
        $stmt->execute();
        $stmt->close();
        }
    }
}
//INSERT INTO ISSUE_TRACKING
$update_descr = "New issue";
if($stmt = $mysqli->prepare('SELECT state_id FROM issue JOIN state ON state.project_id = issue.project_id AND issue.status = state.state_name
WHERE issue_id=?')){
    $stmt->bind_param('s',$issue_id);
    $stmt->execute();
    $stmt->bind_result($state_id);
    if ($stmt->fetch()){
        $stmt->close();
        if ($stmt = $mysqli->prepare('INSERT INTO issue_tracking (issue_id, status, update_descr, updated_by) VALUES (?,?,?,?)')){
        $stmt->bind_param('iisi',$issue_id,$state_id,$update_descr,$_SESSION['id']);
        $stmt->execute();
        $stmt->close();
        }
    }
}
header('Location: issues.php');;
?>
