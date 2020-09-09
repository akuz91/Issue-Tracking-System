<?php
session_start();
include "connectdb.php";
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
//pull out latest status for the issue and find the possible next stauses from the transition table
if ($stmt = $mysqli->prepare('SELECT state_name FROM issue_tracking JOIN transition ON transition.current_state = issue_tracking.status 
JOIN state ON state.state_id = subsequent_state WHERE issue_id = ? AND date = (SELECT MAX(date) FROM issue_tracking WHERE issue_id =?)')) {
    $stmt->bind_param('ii', $_SESSION['issue_id'],$_SESSION['issue_id']);
    $stmt->execute();
    $stmt->bind_result($available_statuses);
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Update Status</title>
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
                    <h2>Update Status</h2>
                </div>
                <div class="update">
                <form action="update_status.php" method="post">
                        <label for="update_descr">Update Description</label> 
                        <input type="text" name="update_descr" style="width: 600px" placeholder="Fixed bug in code" id="update_descr" required>
                        <?php
                        echo "<select name='state'style='width: 400px' required id='state'>";
                        echo "<option>Update Status</option>";
                        while ($stmt->fetch()) {
                            echo "<option value='" . $available_statuses . "'>" . $available_statuses . "</option>";
                        }
                        ?>
                        </select>
                        <input type="submit" value="Update">
                    </form>
                </div>
        <body>
    </html>
<?php
    $stmt->close();
}
?>