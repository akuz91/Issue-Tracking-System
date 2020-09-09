<?php
session_start();
include "connectdb.php";
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//show projects specific to the user logged in
if ($stmt = $mysqli->prepare('SELECT project.project_id, project_name, project_descr, date_created FROM project 
JOIN project_role ON project.project_id = project_role.project_id WHERE user_id = ?')) {
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($project_id, $project_name, $project_descr, $date_created);
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Home Page</title>
            <link href="style.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
            <style>p.ex1 {margin-left: 5%;}</style>
        </head>
        <body class="loggedin">
            <nav class="navtop">
                <div>
                    <h1>Issue Tracking Management System</h1>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
                </div>
            </nav>
            <div class="content">
                <h2>Project List</h2>
                <p>Welcome back, <?=$_SESSION['fname']?>!</p>
            </div>
            <div class="content update">
                <form action="new_project.html" method="post">
                    <input type="submit" value="Create New Project">
                </form>
            </div>
            <div class="content">
                <table>
                    <tr>
                        <th>Project</th>
                        <th>Description</th>
                        <th>Date Created</th>
                    </tr>
                    <?php while ($stmt->fetch()): ?>
                    <tr>
                        <td><a href='issues.php?project_id=<?php echo $project_id; ?>&project_name=<?php echo $project_name; ?>'><?php echo $project_name; ?></a></td>
                        <td><?php echo $project_descr; ?></td>
                        <td><?php echo $date_created; ?></td>
                    </tr>
				    <?php endwhile; ?>
                </table>
            </body>
    </html>
    <?php
    $stmt->close();
}
?>

