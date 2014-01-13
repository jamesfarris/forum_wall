<?php
	session_start();
	require_once('connection.php');
	require_once('functions.php');
	// var_dump($_SESSION);
	if (isset($_POST['action']) && $_POST['action'] == 'post_msg') {
		if (empty($_POST['message']) || $_POST['message'] == '\r') {
			$_SESSION['err_message']['message_error'] = "Can't be blank";
			header('Location: profile.php?id=' . $_POST['id']);
		}
		else {
			$query = "INSERT INTO messages (message, created_at, updated_at, users_id)
					  VALUES ('" . mysqli_real_escape_string($connection, $_POST['message']) . "', 
					  		  NOW(), 
					  		  NOW(),
					  		  '" . $_POST['id'] . "')";

			mysqli_query($connection, $query);
			header('Location: profile.php?id='. $_POST['id']);
		}
	}

	if (isset($_POST['action']) && $_POST['action'] == 'post_comment') {
		if (empty($_POST['comment'])) {
			$_SESSION['err_message']['comment_error'] = "Can't be blank";
			header('Location: profile.php?id=' . $_POST['id']);
		}
		else {
			$query = "INSERT INTO comments (comment, created_at, updated_at, messages_id, users_id)
					  VALUES ('" . mysqli_real_escape_string($connection, $_POST['comment']) . "', 
							  NOW(), 
							  NOW(),
							   " . $_POST['pid'] . ",
							   " . $_SESSION['user_id'] . ")";

			mysqli_query($connection, $query);
			$_SESSION['last_comment'] = mysqli_insert_id($connection);
			header('Location: profile.php?id='. $_SESSION['user_id']);
		}
	}
?>