<?php
session_start(); // Start the session
include 'connection.php';
include 'verifymail.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['formType'] === 'login') {
        // Get login form data
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare and execute the SQL query
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            // User found, verify password
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];
            $username = $row['username'];
            $phone = $row['phone'];
            $admin = $row['admin'];

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['loggedin'] = true; 
                $_SESSION['username'] = $username;
                $_SESSION['phone'] = $phone;
                $_SESSION['admin'] = $admin;
                if ($row['status'] == 1){
                header("Location: index.php");
                exit();
                }else{
                    $errorMessage = "Your account is yet to be verified, Please contact you administrator for verification";
                }
            } else {
                // Invalid password
                $errorMessage = "Invalid password";
            }
        } else {
            // User not found
            $errorMessage = "User not found, please check your credentials";
        }
    } elseif ($_POST['formType'] === 'register') {
        // Get registration form data
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $registerPassword = $_POST['registerPassword'];
        $registerPhoneNumber = $_POST['registerPhoneNumber'];

        // Hash the password
        $hashedPassword = password_hash($registerPassword, PASSWORD_DEFAULT);

           // Generate a unique verification code
     $verificationCode = uniqid();

    $sql = "INSERT INTO users (username, password, firstname, lastname, email, phone, verification_code) VALUES ('$username', '$hashedPassword', '$firstname', '$lastname', '$email', '$registerPhoneNumber', '$verificationCode')";
    
    if ($con->query($sql)) {
        // Send the verification email
        $verificationLink = "localhost/Project_1/verify.php?code=" . $verificationCode; 
        sendEmail('kyleaby1@gmail.com',$firstname,$lastname,$email,$registerPhoneNumber,$verificationLink);
    
    // Close the database connection
    $con->close();
}
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Signup</title>
  <link rel="stylesheet" href="style1.css">
</head>

<body>
  <div class="hero">
    <div class="form-box" id="formBox">
      <div class="button-box">
        <div id="btn"></div>
        <button type="button" class="toggle-btn" id="1" onclick="login()">Log in</button>
        <button type="button" class="toggle-btn" id="2" onclick="register()">Register</button>
      </div>
      <form id="login" class="input-group" action="#" method="post">
      <input type="hidden" name="formType" value="login">
        <input type="text" id="login-username" class="input-field" placeholder="Username" name="username" required>
        <input type="password" id="login-password" class="input-field" placeholder="Password" name="password" required><br>
        <a href="forgot.php" class="forgot">Forgotten Password</a>
        <?php if (isset($errorMessage)) { ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php } ?>
        <button type="submit" class="submit-btn">Log in</button>

      </form>
      <!-- Register form and other elements -->

      <form id="register" action="#" class="input-group" method="POST">
      <input type="hidden" name="formType" value="register">
      <input type="text" class="input-field" placeholder="First name" name="firstname" required>
      <input type="text" class="input-field" placeholder="last name" name="lastname" required>
      <input type="text" class="input-field" placeholder="User name" name="username" required>
      <input type="text" class="input-field" placeholder="email@email.com" name="email" required>
      <input type="password" class="input-field" placeholder="Password" name="registerPassword" required>
        <input type="text" class="input-field" placeholder="Phone Number" name="registerPhoneNumber" required>
        <button type="submit" class="submit-btn">Register</button>
      </form>
    </div>
  </div>

  <script>
    var x = document.getElementById('login');
    var y = document.getElementById('register');
    var z = document.getElementById('btn');
    var formBox = document.getElementById('formBox');
    var btn_register = document.getElementById('2');
    var btn_login = document.getElementById('1');

    function register() {
      x.style.left = "-400px";
      y.style.left = "30px";
      z.style.left = "110px";
      btn_register.style.color = "white";
      btn_login.style.color = "black";
      formBox.style.height = "600px";

    }

    function login() {
      x.style.left = "30px";
      y.style.left = "430px";
      z.style.left = "0px";
      btn_login.style.color = "white";
      btn_register.style.color = "black";
      formBox.style.height = "430px";
    }
  </script>
</body>

</html>