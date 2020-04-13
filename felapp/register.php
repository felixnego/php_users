<?php
  require 'config.php';

  $userErr = $passErr = $pass2Err = $sexErr = $civilErr = $nameErr
    = $firstNameErr = $emailErr = $fileErr = $phoneErr = "";

  $user = $pass = $pass2 = $sex = $civil = $name = $firstName = $email = $phone = "";

  if($_SERVER["REQUEST_METHOD"] == "POST"){

    $userSql = "select * from users where username = '{$_POST['username']}'";
    $userResults = $conn->query($userSql);

    if (empty($_POST['username'])){
      $userErr = "You must set a username!";
    } elseif (preg_match('/^[^0-9].+/', $_POST['username']) == 0){
        $userErr = "Username cannot start with a number!";
    } elseif ($userResults->num_rows > 0) {
        $userErr = "Username already exists!";
    } else {
      $user = $_POST['username'];
    }

      if (empty($_POST['pass'])) {
        $passErr = "You must set a password!";
      } elseif (strlen($_POST['pass']) <= 4) {
        $passErr = "Password must be over 4 characters!";
      } else {
        $pass = $_POST['pass'];
      }

      if (empty($_POST['pass2'])){
        $pass2Err = "Please re-type your password!";
      } elseif ($_POST['pass2'] !== $_POST['pass']) {
        $pass2Err = "Passwords must match!";
      } else {
        $pass2 = $_POST['pass2'];
      }

      if (empty($_POST['sex'])){
        $sexErr = "You must pick a gender!";
      } else {
        $sex = $_POST['sex'];
      }

      if (empty($_POST['civilstatus'])){
        $civilErr = "Please pick a civil status!";
      } else {
        $civil = $_POST['civilstatus'];
      }

      if (empty($_POST['name'])){
        $nameErr = "Name field cannot be empty!";
      } elseif (!preg_match("/^[a-zA-Z -]*$/", $_POST['name'])){
        $nameErr = "Name field cannot contain special characters!";
      } else {
        $name = $_POST['name'];
      }

      if (empty($_POST['firstname'])){
        $firstNameErr = "Frist Name field cannot be empty!";
      } elseif (!preg_match("/^[a-zA-Z -]*$/", $_POST['firstname'])){
        $firstNameErr = "First Name field cannot contain special characters!";
      } else {
        $firstName = $_POST['firstname'];
      }

      $emailSql = "select * from users where email = '{$_POST['email']}'";
      $emailResults = $conn->query($emailSql);

      if (empty($_POST['email'])){
        $emailErr = "Please enter your email address!";
      } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $emailErr = "Invalid email address format!";
      } elseif ($emailResults->num_rows > 0) {
        $emailErr = "Email address is already in use!";
      } else {
        $email = $_POST['email'];
      }

      if (empty($_POST['phone'])){
        $phoneErr = "Please add your phone number!";
      } elseif (!preg_match("/^\+?\d{10}$/", $_POST['phone'])){
        $phoneErr = "Phone number must be digits only and have 10 characters!";
      } else {
        $phone = $_POST['phone'];
      }

      if ($user != '' && $pass != '' && $sex != '' && $civil != ''
        && $name != '' && $firstName != '' && $email != '') {
          require 'upload.php';

          if ($uploadOk == 0) {
            $fileErr = $uploadError;
          } else {
            rename($target_file, "uploads/{$user}.{$imageFileType}");
          }

          $hashed_password = hash('sha512', $pass);
          $insertSql = "insert into users values (
            default,
            '{$user}',
            '{$hashed_password}',
            '{$sex}',
            '{$civil}',
            '{$name}',
            '{$firstName}',
            '{$email}',
            CURDATE(),
            '{$imageFileType}',
            NULL,
            '{$phone}'
          );";
          if($conn->query($insertSql) == TRUE) {
            echo "<p>You have successfully been registered!</p>";
            echo "<br />";
            echo "<a href=\"login.php\">Login</a>";
          } else {
            echo $conn->error;
          }
          exit();
        }
    }

 ?>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="./style/register_style.css">
  </head>
  <body>
    <h2 style="text-align: center">Register</h2>
    <div class="wrapper-center">
      <div id="register-box">
        <form class="" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" name="form1" target="_self" enctype="multipart/form-data">

          <label for="user"
            class="<?php if($userErr !== '') echo 'error' ?>">
            Username:</label>
          <input type="text" name="username"
            value="<?php echo $user; ?>" id="user"><br /><br />
            <span class="error"> <?php echo $userErr; ?> </span><br />

          <label for="pass"
            class="<?php if($passErr !== '') echo 'error' ?>">
            Password:</label>
          <input type="password" name="pass"
            value="<?php echo $pass; ?>" id="pass"><br /><br />
            <span class="error"> <?php echo $passErr; ?> </span><br />

          <label for="pass2"
            class="<?php if($pass2Err !== '') echo 'error' ?>">
            Confirm Password:</label>
          <input type="password" name="pass2"
            value="<?php echo $pass2; ?>" id="pass2"><br /><br />
            <span class="error"> <?php echo $pass2Err; ?> </span><br />

          <label for="sex"
            class="<?php if($sexErr !== '') echo 'error' ?>">
            Sex:</label><br />
          <input type="radio" name="sex" value="m"
            <?php if (isset($sex) && $sex=="m") echo "checked";?>>
            Male<br /><br />
          <input type="radio" name="sex" value="f"
            <?php if (isset($sex) && $sex=="f") echo "checked";?>>
            Female<br /><br />
          <span class="error"> <?php echo $sexErr; ?> </span><br />

          <label for="civilstatus"
            class="<?php if($civilErr !== '') echo 'error' ?>"
            >Civil Status:</label><br />
          <input type="checkbox" name="civilstatus" value="married"
            <?php if (isset($civil) && $civil=="married") echo "checked";?>
            >Married<br /><br />
          <input type="checkbox" name="civilstatus" value="unmarried"
            <?php if (isset($civil) && $civil=="unmarried") echo "checked";?>
            >Unmarried<br /><br />
          <span class="error"> <?php echo $civilErr; ?> </span><br />

          <label for="name"
            class="<?php if($nameErr !== '') echo 'error' ?>"
            >Name:</label>
          <input type="text" name="name"
            value="<?php echo $name; ?>" id="name"><br /><br />
          <span class="error"> <?php echo $nameErr; ?> </span><br />

          <label for="firstname"
            class="<?php if($firstNameErr !== '') echo 'error' ?>"
            >First Name:</label>
          <input type="text" name="firstname"
            value="<?php echo $firstName; ?>" id="firstname"><br /><br />
          <span class="error"> <?php echo $firstNameErr; ?> </span><br />

          <label for="email"
            class="<?php if($emailErr !== '') echo 'error' ?>"
            >Email:</label>
          <input type="text" name="email"
            value="<?php echo $email; ?>" id="email"><br /><br />
          <span class="error"> <?php echo $emailErr; ?> </span><br />

          <label for="phone"
            class="<?php if($phoneErr !== '') echo 'error' ?>"
            >Phone:</label>
          <input type="text" name="phone"
            value="<?php echo $phone; ?>" id="phone"><br /><br />
          <span class="error"> <?php echo $phoneErr; ?> </span><br />

          Select a file to upload:
          <input type="file" name="fileToUpload" id="fileToUpload">
          <span class="error"> <?php echo $fileErr; ?> </span><br />

          <input type="submit" name="Submit" value="Submit"><br /><br />
        </form>
      </div>
    </div>
  </body>
</html>
