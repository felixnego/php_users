<?php
  session_start();

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['old_pass']) || !isset($_POST['new_pass']) ||
      !isset($_POST['new_pass_2'])) {
        echo "<h3 style=\"color:red\">You must set all fields!</h3>";
      } else {
        if ($_SESSION['pass'] != $_POST['old_pass']) {
          echo "<h3 style=\"color:red\">Old password is wrong!</h3>";
        } elseif ($_POST['new_pass'] != $_POST['new_pass_2']) {
          echo "<h3 style=\"color:red\">New passwords do not match!</h3>";
        } elseif ($_POST['old_pass'] == $_POST['new_pass']) {
          echo "<h3 style=\"color:red\">New password cannot be the same as old one!</h3>";
        } elseif (strlen($_POST['new_pass']) < 4 ) {
          echo "<h3 style=\"color:red\">New password is too short!</h3>";
        } else {

          require 'config.php';

          $hashed_password = hash('sha512', $_POST['new_pass']);
          $updateSql = "UPDATE users SET password='{$hashed_password}' WHERE username='{$_SESSION['username']}';";
          $conn->query($updateSql);

          echo "<h3>You password has been updated!</h3>";
          echo "<a href=\"index.php\">Home</a>";

          exit();
        }
      }
  }


 ?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <h2>Reset your Password:</h2>
    <form class="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="form_reset" target="_self">
      <label for="old_pass">Old Password:</label>
      <input type="password" name="old_pass" value="">
      <br>
      <label for="new_pass">New Password:</label>
      <input type="password" name="new_pass" value="">
      <br>
      <label for="new_pass_2">Confirm New Password:</label>
      <input type="password" name="new_pass_2" value="">
      <br>
      <input type="submit" name="Submit" value="Submit">
    </form>
  </body>
</html>
