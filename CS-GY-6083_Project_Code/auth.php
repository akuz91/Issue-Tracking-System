<?php
session_start();
include "connectdb.php";
//check if username and password are filled out
if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}
//check if user_id for who this username and password belong to
if ($stmt = $mysqli->prepare('SELECT user_id, password, first_name FROM user WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
    $stmt->store_result();
    //account exists 
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password, $first_name);
        $stmt->fetch();

        //check if password is correct
        if (password_verify($_POST['password'], $password)) {

            //create sessions to store certain values for the user so they can be accessed across each page
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $user_id;
            $_SESSION['fname'] = $first_name;


            //redirect to homepage
            header('Location: home.php');;
        } else {
            echo 'Incorrect password!';
        }
    } else {
        echo 'Incorrect username!';
    }
	$stmt->close();
}
?>



