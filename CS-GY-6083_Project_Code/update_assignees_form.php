<?php
session_start();
include "connectdb.php";
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//get list of all users who work on project who could potentially be assigned to the issue (excl people already assigned or current user)
$assignee_role = 4;
if ($stmt = $mysqli->prepare('SELECT displayname FROM project_role JOIN user ON user.user_id = project_role.user_id 
WHERE displayname NOT IN (SELECT displayname FROM issue_role JOIN user ON user.user_id = issue_role.user_id
WHERE issue_id = ? AND role_id =?) AND project_id = ? AND user.user_id <>?')) {
    $stmt->bind_param('iiii', $_SESSION['issue_id'],$assignee_role,$_SESSION['project_id'],$_SESSION['id']);
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
                    <h2><?php echo $_SESSION['project_name'] ?> Issue > Add Assignee</h2>
                </div>
                <div class="update">
                    <form action="update_assignees.php" method="post">
                        <?php
                        echo "<select name='displayname'style='width: 200px' required id='displayname'>";
                        echo "<option>Select Assignee</option>";
                        while ($stmt->fetch()) {
                            echo "<option value='" . $displayname . "'>" . $displayname . "</option>";
                        }
                        echo "</select>"; ?>
                        <br></br>
                        <input type="submit" value="Assign">
                    </form>
                </div>
        <body>
    </html>
<?php
    $stmt->close();
}
?>