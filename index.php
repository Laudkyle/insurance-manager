
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Home</title>

</head>

<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="insurance.php">Insurance</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
        <h1>Insurance Details</h1>
        <form action="#" method="post" onsubmit="return validateForm();">
            <label for="compnay_name">Name Of Company</label>
            <input type="text" id="company_name" name="company_name" placeholder="Company name" required><br>

            <label for="client_name">Name Of Client</label>
            <input type="text" id="client_name" name="client_name" placeholder="Name of client" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required><br>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" placeholder="0234567891" required><br>

            <label for="effective_date">Date Effective:</label>
            <input type="date" id="effective_date" name="effective_date" required><br>

            <label for="expiry_date">Expiry Date:</label>
            <input type="date" id="expiry_date" name="expiry_date" required><br>

            <label for="insurance">Type Of Insurance</label>
            <input type="text" id="insurance" name="insurance" placeholder="Type of Insurance" required><br>

            <label for="tenor">Tenor Of Loan Facility</label>
            <input type="text" id="tenor" name="tenor" placeholder="Tenor of loan facility" required><br>

            <label for="amount">Loan Amount</label>
            <input type="text" id="amount" name="amount" placeholder="00.00" required><br>

            <label for="security">Type Of Security</label>
            <input type="text" id="security" name="security" placeholder="Type of security" required><br>

            <input type="submit" value="Submit">
            
            <?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect the user to the login page
    header('Location: auth.php');
    exit;
}
include "connection.php";
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["company_name"])) {
    // Retrieve form data
    $companyName =  mysqli_real_escape_string($con,$_POST['company_name']);
    $clientName =  mysqli_real_escape_string($con,$_POST['client_name']);
    $email =  mysqli_real_escape_string($con,$_POST['email']);
    $phone =  mysqli_real_escape_string($con,$_POST['phone']);
    $effectiveDate =  mysqli_real_escape_string($con,$_POST['effective_date']);
    $expiryDate = mysqli_real_escape_string($con, $_POST['expiry_date']);
    $insurance =  mysqli_real_escape_string($con,$_POST['insurance']);
    $tenor =  mysqli_real_escape_string($con,$_POST['tenor']);
    $amount = mysqli_real_escape_string($con,$_POST['amount']);
    $security =  mysqli_real_escape_string($con,$_POST['security']);

    // SQL query to insert data into the "insurance_entries" table
    $sql = "INSERT INTO insurance_entries (personnel,company_name, client_name, email, phone, effective_date, expiry_date, insurance, tenor,amount,security)
            VALUES ('$username','$companyName', '$clientName', '$email', '$phone','$effectiveDate', '$expiryDate', '$insurance', '$tenor','$amount','$security')";

    if ($con->query($sql)) {
        echo "<p style='text-align: center; color: green;'>Form Submitted Successfully!</p>";
        echo "<meta http-equiv='refresh' content='5;url=index.php'>";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}
?>
        </form>
    </div>
</body>

</html>