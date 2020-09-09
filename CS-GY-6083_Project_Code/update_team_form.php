<?php
session_start();
include "connectdb.php";
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//get list of all users who don't work on the project (as either PM or team member)
$pm_role = 1;
$team_role =2;
if ($stmt = $mysqli->prepare('SELECT displayname FROM user WHERE displayname NOT IN(SELECT displayname FROM project 
    JOIN project_role ON project.project_id = project_role.project_id JOIN user ON project_role.user_id = user.user_id 
    WHERE project.project_id= ? AND (project_role.role_id= ? OR project_role.role_id =?)) AND user.user_id <>?;')) {
    $stmt->bind_param('iiii', $_SESSION['project_id'],$pm_role, $team_role,$_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($displayname);
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Add Assignee</title>
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
            <link href="style.css" rel="stylesheet" type="text/css">
        </head>
        <body class="loggedin">
            <nav class="navtop">
                <div>
                    <h1><a href='home.php?>'>Issue Tracking Management System </a></h1>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                </div>
                <div class="content">
                    <h2><?php echo $_SESSION['project_name'] ?> > Add Team Member</h2>
                </div>
                <div class="update">
                    <form action="update_team.php" method="post">
                        <?php
                        echo "<select name='displayname'style='width: 250px' required id='displayname'>";
                        echo "<option>Select New Team Member</option>";
                        while ($stmt->fetch()) {
                            echo "<option value='" . $displayname . "'>" . $displayname . "</option>";
                        }
                        echo "</select>"; ?>
                        <br>
                        <input type="submit" value="Add">
                        </br>
                    </form>
                </div>
        <body>
    </html>
<?php
    $stmt->close();
}
?>