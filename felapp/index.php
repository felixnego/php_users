<?php
	session_start();

	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo "<h3>Welcome, {$_SESSION['username']}!</h3>
			<a href=\"logout.php\">Logout</a>

			<a href=\"change_pass.php\"> Reset Password</a>

			<a href=\"email.php\"> Email</a>";
	}
 ?>

<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>FelixApp</title>
	</head>
	<body>
		<h1>Welcome to FelApp</h1>
    <?php
			if (!isset($_SESSION['loggedin'])) {
				echo "<a href=\"register.php\">Register </a>";
				echo "<a href=\"login.php\">Login</a>";
			}
		 ?>
		<h2>Our Users</h2>
		<?php
				require 'config.php';

				if (!isset($_SESSION['loggedin'])) {
					echo "<ul>";

					$usersSql = "select username from users;";
					$usersResults = $conn->query($usersSql);
					while($row = $usersResults->fetch_assoc()) {
						echo "<li>" . $row['username'] . "</li>";
					}

					echo "</ul>";
				} else {
					echo "<ol>";

					$usersSql = "select * from users order by name {$_SESSION['order']};";
					$usersResults = $conn->query($usersSql);

					while($row = $usersResults->fetch_assoc()) {
						if (isset(glob("uploads/{$row['username']}.*")[0])) {
							$pictureFile = glob("uploads/{$row['username']}.*")[0];
						} else {
							$pictureFile = "";
						}

						echo "<li>" . $row['name'] . " - " . $row['firstname']
							. " - " . $row['username'] . " - " . $row['email']
							. " - " . $row['sex'] . " - " . $row['civilstatus']
							. " - " . $row['dateregistered']
							. " - " . $row['phone']
							. " last seen at: " . $row['datalogin']
							. "<img src={$pictureFile} height=\"50\" width=\"50\" alt=\"no picture\"/>"
							. "</li>";
					}
				}

		 ?>
	</body>
</html>
