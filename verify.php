<?php
include 'connection.php';

if (isset($_GET['code'])) {
    $verificationCode = $_GET['code'];

    // Check if the verification code exists in the database
    $sql = "SELECT * FROM users WHERE verification_code = '$verificationCode'";
    $result = $con->query($sql);

    if ($result->num_rows === 1) {
        // Update the user's status to verified
        $updateSql = "UPDATE users SET status = 1 WHERE verification_code = '$verificationCode'";
        if ($con->query($updateSql)) {
            echo "Account verified successfully!";
        } else {
            echo "Error updating account status: " . $con->error;
        }
    } else {
        echo "Invalid verification code.";
    }

    // Close the database connection
    $con->close();
} else {
    echo "Verification code not provided.";
}
?>
