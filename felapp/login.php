<?php
  session_start();

  if (isset($_POST['username']) && isset($_POST['pass'])){
    require 'config.php';

    $hashed_password = hash('sha512', $_POST['pass']);

    if ($conn->connect_error){
      die("Connection to MySQL failed with status: " . $conn->connect_error);
    }
    $conn->query("USE userapp");
    $sql = "select * from users where username = '{$_POST['username']}' and password = '{$hashed_password}'";

    $results = $conn->query($sql);

    if ($results->num_rows > 0){
      $row = $results->fetch_assoc();
      $_SESSION['loggedin'] = TRUE;
      $_SESSION['username'] = $row['username'];
      $_SESSION['pass'] = $_POST['pass'];
      $_SESSION['order'] = $_POST['order'];

      $conn->query("UPDATE users SET datalogin = NOW() WHERE username = '{$_POST['username']}';");

      header('location: index.php');
      } else {
        echo "<h2>Invalid Credentials!</h2>";
      }
      exit();
    }

 ?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <h2>Login:</h2>
    <form class="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="form1" target="_self">
      <label for="user">Username:</label>
      <input type="text" name="username" value="" id="user">
      <label for="user">Password:</label>
      <input type="password" name="pass" value="" id="pass">
      <br>
      <input type="radio" name="order" value="asc">Order Names Asc
      <input type="radio" name="order" value="desc">Order Names Desc
      <input type="submit" name="Submit" value="Submit">
    </form>
  </body>
</html>
