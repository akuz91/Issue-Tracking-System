<?php
include "connectdb.php";

//entries validation
if (preg_match('/[A-Za-z]+/', $_POST['first_name']) == 0) {
    exit('First name is not valid');
}
if (preg_match('/[A-Za-z]+/', $_POST['last_name']) == 0) {
    exit('First name is not valid');
}
if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
    exit('Username is not valid');
}
if (strlen($_POST['password']) > 16 || strlen($_POST['password']) < 8) {
	exit('Password must be between 8 and 16 characters long');
}
if (strlen($_POST['displayname']) > 30 || strlen($_POST['displayname']) < 8) {
	exit('Display name must be between 8 and 30 characters long');
}
if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid');
}
//check if account with the same username already exists
if ($stmt = $mysqli->prepare('SELECT user_id, password FROM user WHERE username = ?')) {
	// bind params
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		//username already exists
		echo 'Username exists, please choose another!';
	} else {
		//username doesn't exist, insert new user
		if ($stmt = $mysqli->prepare('INSERT INTO user (first_name, last_name, username, password, displayname, user_email) VALUES (?, ?, ?, ?, ?, ?)')) {
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$stmt->bind_param('ssssss', $_POST['first_name'], $_POST['last_name'], $_POST['username'], $password, $_POST['displayname'],$_POST['user_email']);
			$stmt->execute();
			header('Location: index.html');
		} else {
			echo 'Could not prepare statement!';
		}
	}
	$stmt->close();
} else {
	echo 'Could not prepare statement!';
}
$mysqli->close();
?>