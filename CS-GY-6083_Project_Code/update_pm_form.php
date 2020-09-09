<?php
session_start();
include "connectdb.php";
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//get list of all users who don't work on the project (as either PL or team member)
$pm_role =1;
if ($stmt = $mysqli->prepare('SELECT displayname FROM user WHERE displayname NOT IN (SELECT displayname FROM project_role
JOIN user ON user.user_id = project_role.user_id WHERE project_id = ? AND role_id = ?)')) {
    $stmt->bind_param('ii', $_SESSION['project_id'],$pm_role);
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
                    <h2><?php echo $_SESSION['project_name'] ?> > Add Project Lead</h2>
                </div>
                <div class="update">
                    <form action="update_pm.php" method="post">
                        <?php
                        echo "<select name='displayname'style='width: 250px' required id='displayname'>";
                        echo "<option>Select New Project Lead</option>";
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