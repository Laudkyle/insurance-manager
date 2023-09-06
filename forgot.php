<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate the form inputs
    if (empty($username) || empty($phone) || empty($newPassword) || empty($confirmPassword)) {
        $errorMessage = "Please fill in all fields.";
    } elseif ($newPassword !== $confirmPassword) {
        $errorMessage = "Passwords do not match.";
    } else {
        // Connect to the database
       include 'connection.php';

        // Prepare and execute the SQL query
        $sql = "SELECT * FROM users WHERE username = '$username' AND phone = '$phone'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            // User found, update the password
            $row = $result->fetch_assoc();
            $userId = $row['id'];

            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateSql = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";
            if ($con->query($updateSql) === TRUE) {
                // Password updated successfully
                $errorMessage = "Password has been reset successfully. 
                                Please wait while we redirect!!";
                    echo '<script>setTimeout(function(){ window.location.href = "auth.php"; }, 3000);</script>';
            } else {
                $errorMessage = "Error updating password: " . $con->error;
            }
        } else {
            // User not found
            $errorMessage = "Invalid full name or phone number.";
        }

        // Close the database connection
        $con->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url(gas-station.jpg);
        }

        form {
            width: 400px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }
h1{
    color:#ff0000;
    text-align:center;
}
        label {
            display: block;
            margin-bottom: 5px;

        }

        input[type="text"],
        input[type="password"] {
            width: 94%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background: #4caf50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .error-message {
            color: #ff0000;
            margin-bottom: 10px;
        }

        .success-message {
            color: #008000;
            margin-bottom: 10px;
        }

        @media (max-width: 700px) {
            form {
                width: 300px;
                margin-bottom: 180px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
            </div>
            <?php if (isset($errorMessage)) { ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php } ?>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>

</html>