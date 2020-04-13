<?php
  session_start();

  $toErr = $msgErr = $subErr = "";
  $to = $msg = $sub = "";

  if($_SERVER["REQUEST_METHOD"] == "POST"){

    require 'config.php';

    $conn->query("USE userapp");

    $toResults = $conn->query("SELECT email FROM users WHERE username = '{$_POST['to']}';");

    if (empty($_POST['to'])) {
      $toErr = "The 'To' field cannot be empty!";
    } elseif ($toResults->num_rows == 0) {
      $toErr = "No user with that username found!";
    } else {
      $to = $toResults->fetch_assoc();
    }

    if (empty($_POST['sub'])) {
      $subErr = "The 'Subject' field cannot be empty!";
    } else {
      $sub = $_POST['sub'];
    }

    if (empty($_POST['msg'])) {
      $msgErr = "Please enter a message!";
    } else {
      $msg = $_POST['msg'];
    }

    if ($toErr != "" && $subErr != "" && $msgErr != ""){

      $fromResult = $conn->query("SELECT email FROM users WHERE username = '{$_SESSION['username']}';");
      $sender = $fromResult->fetch_assoc(); // gets a row

      $header = "From:{$sender['email']} \r\n";
      $header .= "MIME-Version: 1.0\r\n";
      $header .= "Content-type: text/html\r\n";

      $msg .= "Acest mail a fost trimis de pe FelApp";

      $retval = mail($to,$sub,$msg,$header);

           if( $retval == true ) {
              echo "Message sent successfully...";
              exit();
           } else {
              echo "Message could not be sent...";
           }
    }

  }

 ?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Send Email</title>
  </head>
  <body>
    <h2>Send an Email to Another User:</h2>
    <form class="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="form1" target="_self">

      <label for="to"
        class="<?php if($toErr !== '') echo 'error' ?>"
        >To (username):</label>
      <input type="text" name="to"
        value="<?php echo $to; ?>"><br /><br />
      <span class="error"> <?php echo $toErr; ?> </span><br />

      <label for="sub"
        class="<?php if($subErr !== '') echo 'error' ?>"
        >Subject:</label>
      <input type="text" name="sub"
        value="<?php echo $sub; ?>"><br /><br />
      <span class="error"> <?php echo $subErr; ?> </span><br />

      <label for="msg"
        class="<?php if($msgErr !== '') echo 'error' ?>"
        >Message:</label>
      <input type="textarea" name="msg"
        value="<?php echo $msg; ?>"><br /><br />
      <span class="error"> <?php echo $msgErr; ?> </span><br />

      <input type="submit" name="Submit" value="Submit"><br /><br />

    </form>
  </body>
</html>
