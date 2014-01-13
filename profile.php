<?php
	session_start();
	require_once('connection.php');
	require_once('functions.php');
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}
	if (isset($_POST['remove']) && $_POST['remove'] == 'delete') {
		$id = $_POST['mid'];
		$person = $_SESSION['user_id'];
		
		$query = "DELETE FROM comments
				  WHERE messages_id = $id";

		mysqli_query($connection, $query);

		$query = "DELETE FROM messages 
				  WHERE id = $id AND users_id = $person";

		mysqli_query($connection, $query);
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>The Wall</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Carrois+Gothic+SC' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="styles.css">
	<script>
		$(document).ready(function(){
			$('.c<?= $_SESSION['last_comment'] ?>').hide();
			$('.c<?= $_SESSION['last_comment'] ?>').fadeIn('slow');
		});
	</script>
</head>
<body>
	<div class="header">
		<h1>The Wall</h1>
		<p>
			<?php
				if (isset($_SESSION['user_id'])) 
				{
					$query = "SELECT id, first_name, last_name, email
						  FROM users
						  WHERE id = " . $_SESSION['user_id'];
					$result = mysqli_query($connection, $query);
					$row = mysqli_fetch_assoc($result);
				} 
				else
				{
					header('Location: index.php');
				}
				if (isset($_SESSION['user_id'])) {
					echo 'Welcome ' . $row['first_name'] . ' ' . $row['last_name'] . '<a class="logout" href="process.php?logout=1">Logout</a><br>'
					. '<a href="mailto:' . $row['email'] . '">' . $row['email'] . '</a>';
				} else if (!isset($_SESSION['user_id'])) {
				}
			?>
		</p>	
	</div>
	<?= (isset($_SESSION['err_message']['comment_error']) ? '<p class="error">' . $_SESSION['err_message']['comment_error'] . '</p>' : '') ?>
	<?= (isset($_SESSION['message']) ? '<div class="success"><p>' . $_SESSION['message'] . '</p></div>' : ''); ?>
	<div class="messages">
		<h1>Post a message</h1>
		<?= (isset($_SESSION['err_message']['message_error']) ? '<p class="error">'.$_SESSION['err_message']['message_error'].'</p>' : '') ?>
		<form id="message_post" action="post_message.php" method="post">
			<input type="hidden" name="action" value="post_msg">
			<input type="hidden" name="id" value="<?= $row['id'] ?>">
			<textarea name="message" id="message" cols="82" rows="6"></textarea>
			<input type="submit" value="Post">
		</form>
		<div>
			<?php
				$query = "SELECT users.id AS user_id, users.first_name, users.last_name, 
						  messages.id AS message_id, messages.message, messages.created_at AS m_created_at 
						  FROM users
						  LEFT JOIN messages ON users.id = users_id
						  WHERE messages.id >= 0
						  ORDER BY message_id DESC";

				$messages = fetchAll($connection, $query);

				foreach ($messages as $message) 
				{
				 ?>
					<div>
						<p class="created_by">Posted by: <?= $message['first_name'] . ' ' . $message['last_name'] . '<span class="right">' . time_elapsed_string($message['m_created_at']) . '</span>' ?></p>
						<p class="message"><?= $message['message'] ?></p>
						<form class="delete_form" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
							<input type="hidden" name="remove" value="delete">
							<input type="hidden" name="mid" value=" <?= $message['message_id'] ?>">
							<input type="hidden" name="pid" value=" <?= $message['user_id'] ?>">
							<input type="submit" value="Delete">
						</form>
						<ol>
						<?php
							$msgid = $message['message_id'];
							$query = "SELECT comments.comment, comments.id as cid, comments.created_at AS cdate, comments.messages_id, CONCAT_WS(' ', users.first_name, users.last_name) AS user_name, users.id AS person_id
									  FROM comments
									  LEFT JOIN users ON comments.users_id = users.id
									  WHERE messages_id = $msgid
									  ORDER BY comments.id ASC";
							$comments = fetchAll($connection, $query);
							foreach ($comments as $comment) 
							{
							?>
							<div class="comment-block c<?= $comment['cid'] ?>">
								<p class="comment-by"><?= $comment['user_name'] ?> replied <?=  time_elapsed_string($comment['cdate']) ?></p>
								<p class="comments"><?= $comment['comment'] ?></p>
							</div>
							<?php	
							}
						?>
						</ol>
						<form class="comment_form" action="post_message.php" method="post">
							<input type="hidden" name="action" value="post_comment">
							<input type="hidden" name="id" value="<?= (isset($comment['person_id']) ? $comment['person_id'] : '') ?>">
							<input type="hidden" name="pid" value="<?= $message['message_id']; ?>">
							<textarea name="comment" id="comment" cols="82" rows="5"></textarea>
							<input type="submit" value="Add Comment">
						</form>
					</div>
				<?php
				}
				?>
		</div>
	</div>
</body>
</html>
<?php
	unset($_SESSION['err_message']['comment_error']);
	unset($_SESSION['err_message']['message_error']);
	unset($_SESSION['message']);
	unset($_SESSION['last_post']);
?>