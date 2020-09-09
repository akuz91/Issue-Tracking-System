<?php
session_start();
include "connectdb.php";
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//get list of all users who work on project who could potentially be assigned to the issue (excluding user reporting it)
if ($stmt = $mysqli->prepare('SELECT displayname FROM project JOIN project_role ON project.project_id = project_role.project_id 
JOIN user ON project_role.user_id = user.user_id WHERE project.project_id= ? AND user.user_id <> ?')) {
    $stmt->bind_param('ii', $_SESSION['project_id'],$_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($displayname);
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>New Project</title>
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
                    <h2><?php echo $_SESSION['project_name'] ?> > Create Issue</h2>
                </div>
                <div class="update">
                    <form action="new_issue.php" method="post">
                        <input type="hidden" value="<?php echo $project_id?>" name="var">
                        <label for="issue_title">Issue Title</label>
                        <label for="issue_descr">Issue Description</label> 
                        <input type="text" name="issue_title" placeholder="Problem X" id="issue_title" required>
                        <input type="text" name="issue_descr" placeholder="A big problem!" id="issue_descr" required>
                        <?php
                        echo "<select name='displayname'style='width: 400px' required id='displayname'>";
                        echo "<option>Select Assignee</option>";
                        while ($stmt->fetch()) {
                            echo "<option value='" . $displayname . "'>" . $displayname . "</option>";
                        }
                        echo "</select>"; ?>
                        <br></br>
                        <input type="submit" value="Create Issue">
                    </form>
                </div>
        <body>
    </html>
<?php
    $stmt->close();
}
?>
