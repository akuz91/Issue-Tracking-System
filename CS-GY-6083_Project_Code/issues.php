<?php
session_start();
include "connectdb.php";
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//getting the project_id depends on which page we are coming from
//if we are coming from the home page, we need to get the project_id that the user clicked on
if(isset($_GET["project_id"])) {
    $project_id = $_GET["project_id"];
    $project_name = $_GET['project_name'];

    $_SESSION['project_id'] = $project_id;
    $_SESSION['project_name'] = $project_name;
} 
//if we are coming from the new_issue page, we need the system to remember which project's issues we were looking at 
//grab project_id from the session project_id
else{
    $project_id = $_SESSION['project_id'];
    $project_name = $_SESSION['project_name'];
}
//get list of PLs for particular project to be displayed at top of pg
$pm_role =1;
$stmt = $mysqli->prepare('SELECT displayname FROM project_role JOIN user ON user.user_id = project_role.user_id 
WHERE project_id = ? AND role_id = ?');
$stmt->bind_param('ii', $_SESSION['project_id'],$pm_role);
$stmt->execute();
$stmt->bind_result($result);
$pms = array();
while ($stmt->fetch()) {
    $pms[] =htmlspecialchars( $result, ENT_NOQUOTES, 'UTF-8' );
}
$stmt->close();

//get list of team members for particular project to be displayed at top of pg
$team_role = 2;
$stmt = $mysqli->prepare('SELECT displayname FROM project_role JOIN user ON user.user_id = project_role.user_id 
WHERE project_id = ? AND role_id = ?');
$stmt->bind_param('ii', $_SESSION['project_id'],$team_role);
$stmt->execute();
$stmt->bind_result($result);
$team = array();
while ($stmt->fetch()) {
    $team[] =htmlspecialchars( $result, ENT_NOQUOTES, 'UTF-8' );
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Project Issues: <?php echo $project_name?></title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    </head>
    <body class="loggedin">
        <nav class="navtop">
            <div>
                <h1><a href='home.php?>'>Issue Tracking Management System </a></h1>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </nav>
        <div class="content">
            <h2><?php echo $project_name?></h2>
            <?php
            //check if user is the PM on this project and then show the option to edit PMs or team members, otherwise don't
            $stmt = $mysqli->prepare('SELECT displayname FROM project_role JOIN user ON user.user_id = project_role.user_id 
            WHERE project_id = ? AND user.user_id = ? AND role_id = ?');
                $stmt->bind_param('iii', $_SESSION['project_id'], $_SESSION['id'],$pm_role);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                ?>
                <i>Project Leads: <?php echo implode(', ', $pms) ?>  <a href="update_pm_form.php"><i class="fas fa-pen fa-xs"></i></i></a><br>
                <i>Team Members: <?php echo implode(', ', $team) ?>  <a href="update_team_form.php"><i class="fas fa-pen fa-xs"></i></i></a></br>
                <?php 
                } 
                else{ ?>
                <i>Project Leads: <?php echo implode(', ', $pms) ?>  </i><br>
                <i>Team Members: <?php echo implode(', ', $team) ?>  </i></br>
                <?php
                }
                ?>
        </div>
        <div class="content update">
            <form action="new_issue_form.php" method="post">
                <input type="submit" value="Create New Issue">
            </form>
        </div>
        <div class="content update">
        <form name="Search"  method="post">
            <input type="text" name="search" placeholder="Search for word or phrase">
            <input type="submit" value="Search" style="width:75px">
        </form>
        <br><table>
                <tr>
                    <th>Issue</th>
                    <th>Description</th>
                    <th>Date Reported</th>
                    <th>Status</th>
                </tr>
        <?php 
        //check if user has entered search word, perform query to find where word appears and show those rows for the issues
        if(isset($_POST['search'])){
            $input = "%{$_POST['search']}%";
            $search = htmlspecialchars( $input, ENT_NOQUOTES, 'UTF-8' );
            $filter = $mysqli->prepare('SELECT issue_id, issue_title, issue_descr, date_reported, status FROM issue 
            JOIN project ON project.project_id = issue.project_id WHERE project.project_id = ? AND (issue_title LIKE ? OR issue_descr LIKE ?)');
            $filter->bind_param('iss', $project_id, $search, $search);
            $filter->execute();
            $filter->bind_result($issue_id, $issue_title, $issue_descr, $date_reported, $status);
            while ($filter->fetch()):?>
        <tr>
            <td><a href='issue_history.php?project_name=<?php echo $project_name; ?>&issue_id=<?php echo $issue_id; ?>&issue_title=<?php echo $issue_title; ?>'><?php echo $issue_title; ?></a></td>
            <td><?php echo $issue_descr; ?></td>
            <td><?php echo $date_reported; ?></td>
            <td><?php echo $status; ?></td>
        </tr>
        <?php endwhile; 
        } 
        else{
            //user has not entered search word, perform query to show all of the issue's history
            $stmt = $mysqli->prepare('SELECT issue_id, issue_title, issue_descr, date_reported, status FROM issue 
            JOIN project ON project.project_id = issue.project_id WHERE project.project_id = ?');
            $stmt->bind_param('i', $project_id);
            $stmt->execute();
            $stmt->bind_result($issue_id, $issue_title, $issue_descr, $date_reported, $status);
            ?></br>
            <?php while ($stmt->fetch()): ?>
            <tr>
                <td><a href='issue_history.php?project_name=<?php echo $project_name; ?>&issue_id=<?php echo $issue_id; ?>&issue_title=<?php echo $issue_title; ?>'><?php echo $issue_title; ?></a></td>
                <td><?php echo $issue_descr; ?></td>
                <td><?php echo $date_reported; ?></td>
                <td><?php echo $status; ?></td>
            </tr>
            <?php endwhile; }?></br>
            </table>
        </body>
</html>


