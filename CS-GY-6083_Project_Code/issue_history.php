<?php
session_start();
include "connectdb.php";
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
if(isset($_GET["issue_id"])) {
    $issue_id = $_GET["issue_id"];
    $issue_title = $_GET["issue_title"];
    $_SESSION['issue_id'] = $issue_id;
    $_SESSION['issue_title'] = $issue_title;
}else{
    $issue_id = $_SESSION['issue_id'];
    $issue_title = $_SESSION['issue_title'];
}
//get list of assignees for particular issue to be displayed at top of pg
$assign_role = 4;
$stmt = $mysqli->prepare('SELECT displayname FROM issue_role JOIN user ON user.user_id = issue_role.user_id 
WHERE issue_id = ? AND role_id = ?');
$stmt->bind_param('ii', $_SESSION['issue_id'], $assign_role);
$stmt->execute();
$stmt->bind_result($result);
$assignees = array();
while ($stmt->fetch()) {
    $assignees[] =htmlspecialchars( $result, ENT_NOQUOTES, 'UTF-8' );
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Issue History: <?php echo $_SESSION['project_name']?></title>
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
            <h2><?php echo $_SESSION['project_name']?> > Issue: <?php echo $issue_title?></h2>
            <?php
            //check if user is the PM on this project and then show the option to add assignees, otherwise don't
            $pm_role = 1;
            $stmt = $mysqli->prepare('SELECT displayname FROM project_role JOIN user ON user.user_id = project_role.user_id 
            WHERE project_id = ? AND user.user_id = ? AND role_id = ?');
                $stmt->bind_param('iii', $_SESSION['project_id'], $_SESSION['id'],$pm_role);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                ?>
                <i>Assignees:<i>  </i><?php echo implode(', ', $assignees) ?>  <a href="update_assignees_form.php"><i class="fas fa-pen fa-xs"></i></i></a>
                <?php 
                } 
                else{ ?>
                <i>Assignees:<i>  </i><?php echo implode(', ', $assignees) ?></i>
                <?php
                }
                ?>
        </div>
        <?php
        //check if user is a PM on the project or an assignee and then show them the "update status" button, otherwise don't
        if ($stmt = $mysqli->prepare('SELECT role_id FROM  project_role WHERE user_id = ? AND project_id = ? AND role_id = ? UNION 
        SELECT role_id FROM issue_role JOIN issue ON issue.issue_id = issue_role.issue_id WHERE user_id = ? AND issue.issue_id = ? AND role_id = ?')){
            $stmt->bind_param('iiiiii', $_SESSION['id'],$_SESSION['project_id'],$pm_role,$_SESSION['id'],$issue_id, $assign_role);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($role_id);
                $stmt->fetch();
                $stmt->close();
            ?>              
            <div class="content update">
                <form action="update_status_form.php" method="post">
                    <input type="submit" value="Update Status">
                </form>
            </div>
            <?php
            }
        }
        ?>
        <div class="content update">
            <form name="Search"  method="post">
            <input type="text" name="search" placeholder="Search for word or phrase">
            <input type="submit" value="Search" style="width:75px">
        </form>
            <table>
                <tr>
                    <th>Description</th>
                    <th>Updated By</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
        <?php 
        //check if user has entered search word, perform query to find where word appears and show those rows for the issue's history
        if(isset($_POST['search'])){
            $input = "%{$_POST['search']}%";
            $search = htmlspecialchars( $input, ENT_NOQUOTES, 'UTF-8' );
            $filter= $mysqli->prepare('SELECT update_descr, displayname, date, state_name FROM issue_tracking 
            JOIN state ON issue_tracking.status = state.state_id JOIN user ON issue_tracking.updated_by = user.user_id 
            WHERE issue_id = ? AND (state_name LIKE ? OR update_descr LIKE ? OR displayname LIKE ?) ORDER BY date DESC');
            $filter->bind_param('isss', $issue_id, $search, $search, $search);
            $filter->execute();
            $filter->bind_result($update_descr, $updated_by, $date, $status);
            while ($filter->fetch()):?>
        <tr>
            <td><?php echo $update_descr; ?></td>
            <td><?php echo $updated_by; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo $status; ?></td>
        </tr>
        <?php endwhile; 
        } 
        else{
            //if no search query, show table of issue history
            $stmt = $mysqli->prepare('SELECT update_descr, displayname, date, state_name FROM issue_tracking 
            JOIN state ON issue_tracking.status = state.state_id JOIN user ON issue_tracking.updated_by = user.user_id 
            WHERE issue_id = ? ORDER BY date DESC');
            $stmt->bind_param('i', $issue_id);
            $stmt->execute();
            $stmt->bind_result($update_descr, $updated_by, $date, $status);
            while ($stmt->fetch()): ?>
            <tr>
            <td><?php echo $update_descr; ?></td>
            <td><?php echo $updated_by; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo $status; ?></td>
            </tr>
            <?php endwhile; ?>
            </table>
        </body>
</html>
<?php
$stmt->close();
}
?>