<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect the user to the login page
    header('Location: auth.php');
    exit;
}

// Include your database connection file here
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $entryId = $_GET["id"];

    // Query the database to retrieve the entry with the specified ID
    $sql = "SELECT * FROM insurance_entries WHERE id = $entryId";
    $result = $con->query($sql);

    if ($result->num_rows == 1) {
        // Fetch the entry data
        $row = $result->fetch_assoc();

        // Return the entry data as JSON
        echo json_encode($row);
    } else {
        // Return an error message as JSON
        echo json_encode(["error" => "Entry not found"]);
    }
} else {
    // Return an error message as JSON
    echo json_encode(["error" => "Invalid request"]);
}

// Close the database connection
$con->close();
?>
