<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect the user to the login page
    header('Location: auth.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Insurance Entries</title>
   
</head>
<body>
<nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="insurance.php">Insurance</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <h1>Insurance Entries</h1>
    <?php
require 'connection.php';
$username = $_SESSION['username'];
// Function to log updates for individual fields
function logFieldUpdate($username,$entryId, $fieldName, $oldValue, $newValue) {
    global $con;

    $entryId = mysqli_real_escape_string($con, $entryId);
    $fieldName = mysqli_real_escape_string($con, $fieldName);
    $oldValue = mysqli_real_escape_string($con, $oldValue);
    $newValue = mysqli_real_escape_string($con, $newValue);

    $logSql = "INSERT INTO update_entries (personnel,entry_id, updated_fields, old_values, new_values) VALUES ('$username','$entryId', '$fieldName', '$oldValue', '$newValue')";

    $con->query($logSql);
}

// Handle form submission for updating entries
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_entry"])) {
    $id = $_POST["entry_id"];
    $companyName = $_POST["company_name"];
    $clientName = $_POST["client_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $effectiveDate = $_POST["effective_date"];
    $expiryDate = $_POST["expiry_date"];
    $insurance = $_POST["insurance"];
    $tenor = $_POST["tenor"];
    $amount = $_POST['amount'];
    $security = $_POST['security'];

    // SQL query to fetch the old values before the update
    $fetchSql = "SELECT * FROM insurance_entries WHERE id=$id";
    $result = $con->query($fetchSql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check each field for updates
        if ($companyName !== $row["company_name"]) {
             logFieldUpdate($username,$id, "Company Name", $row["company_name"], $companyName);
        }
        if ($clientName !== $row["client_name"]) {
             logFieldUpdate($username,$id, "Client Name", $row["client_name"], $clientName);
        }
        if ($email !== $row["email"]) {
             logFieldUpdate($username,$id, "Email", $row["email"], $email);
        }
        if ($phone !== $row["phone"]) {
             logFieldUpdate($username,$id, "Phone", $row["phone"], $phone);
        }
        if ($effectiveDate !== $row["effective_date"]) {
             logFieldUpdate($username,$id, "Effective Date", $row["effective_date"], $effectiveDate);
        }
        if ($expiryDate !== $row["expiry_date"]) {
             logFieldUpdate($username,$id, "Expiry Date", $row["expiry_date"], $expiryDate);
        }
        if ($insurance !== $row["insurance"]) {
             logFieldUpdate($username,$id, "Insurance", $row["insurance"], $insurance);
        }
        if ($tenor !== $row["tenor"]) {
             logFieldUpdate($username,$id, "Tenor", $row["tenor"], $tenor);
        }
        if ($amount !== $row["amount"]) {
             logFieldUpdate($username,$id, "Amount", $row["amount"], $amount);
        }
        if ($security !== $row["security"]) {
             logFieldUpdate($username,$id, "Security", $row["security"], $security);
        }

        // Update the entry in the "license_entries" table
        $updateSql = "UPDATE insurance_entries SET company_name='$companyName', client_name='$clientName', email='$email', phone='$phone', effective_date='$effectiveDate', expiry_date='$expiryDate', insurance='$insurance', tenor='$tenor', amount='$amount', security='$security' WHERE id=$id";

        if ($con->query($updateSql)) {
            echo "<p style='color: green;'>Entry updated successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error updating entry: " . $con->error . "</p>";
        }
    }
}   
 // Handle form submission for deleting entries
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_entry"])) {
    $id = $_POST["entry_id"];

    // SQL query to delete the entry from the "license_entries" table
    $deleteSql = "DELETE FROM insurance_entries WHERE id=$id";

    if ($con->query($deleteSql)) {
        echo "<p style='color: green;'>Entry deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error deleting entry: " . $con->error . "</p>";
    }
}

// Query the database to retrieve entries
$sql = "SELECT *, CASE WHEN expiry_date >= CURDATE() THEN 'Current' ELSE 'Expired' END AS status FROM insurance_entries";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Display the table header
    echo "<table>";
    echo "<tr><th>Company Name</th><th>Client Name</th><th>Email</th><th>Phone</th><th>Effective Date</th><th>Expiry Date</th><th>Insurance</th><th>Tenor</th><th>Loan Amount</th><th>Type of Security</th><th>Status</th><th>Update</th><th>Delete</th></tr>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["company_name"] . "</td>";
        echo "<td>" . $row["client_name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";
        echo "<td>" . $row["effective_date"] . "</td>";
        echo "<td>" . $row["expiry_date"] . "</td>";
        echo "<td>" . $row["insurance"] . "</td>";
        echo "<td>" . $row["tenor"] . "</td>";
        echo "<td>" . $row["amount"] . "</td>";
        echo "<td>" . $row["security"] . "</td>";
        echo "<td>" . $row["status"] . "</td>";
        echo "<td><input type = 'submit' value='Update' class='update-button' data-id='" . $row["id"] . "'></td>";
        echo "<td>
            <form method='post' onsubmit='return confirm(\"Are you sure you want to delete this entry?\");'>
                <input type='hidden' name='entry_id' value='" . $row["id"] . "'>
                <input type='submit' name='delete_entry' value='Delete'>
            </form>
        </td>";
        echo "</tr>";
    }

    // Close the table
    echo "</table>";
} else {
    echo "<p>No entries found in the database.</p>";
}

// Close the database connection
$con->close();
?>



<!-- Modal -->
<div id="update-modal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <!-- Update Form -->
        <h2>Update Insurance Entry</h2>
        <form action="#" method="post" onsubmit="return validateUpdateForm()">
            <input type="hidden" id="entry_id" name="entry_id">
            <label for="company_name">Name Of Company</label>
            <input type="text" id="update_company_name" name="company_name" required><br><br>

            <label for="client_name">Name Of Client</label>
            <input type="text" id="update_client_name" name="client_name" required><br><br>

            <label for="email">Email</label>
            <input type="email" id="update_email" name="email" required><br><br>

            <label for="phone">Phone Number</label>
            <input type="text" id="update_phone" name="phone" required><br><br>

            <label for="effective_date">Date Effective</label>
            <input type="date" id="update_effective_date" name="effective_date" required><br><br>

            <label for="expiry_date">Expiry Date</label>
            <input type="date" id="update_expiry_date" name="expiry_date" required><br><br>

            <label for="insurance">Type Of Insurance</label>
            <input type="text" id="update_insurance" name="insurance" required><br><br>

            <label for="tenor">Tenor Of Loan Facility</label>
            <input type="text" id="update_tenor" name="tenor" required><br><br>
            
            <label for="amount">Loan Amount</label>
            <input type="text" id="update_amount" name="amount" placeholder="00.00" required><br>

            <label for="security">Type Of Security</label>
            <input type="text" id="update_security" name="security" placeholder="Type of security" required><br>

            <input type="submit" name="update_entry" value="Update">
        </form>
    </div>
</div>

<script>
    // Function to open the modal
    function openModal() {
        const modal = document.getElementById('update-modal');
        modal.style.display = 'block';
    }

    // Function to close the modal
    function closeModal() {
        const modal = document.getElementById('update-modal');
        modal.style.display = 'none';
    }

    // Get all product name cells and update buttons
    const productNames = document.querySelectorAll('.product-name');
    const updateButtons = document.querySelectorAll('.update-button');

    // Get the update form elements
    const entryIdInput = document.getElementById('entry_id');
    const updateCompanyName = document.getElementById('update_company_name');
    const updateClientName = document.getElementById('update_client_name');
    const updateEmailInput = document.getElementById('update_email');
    const updatePhoneInput = document.getElementById('update_phone');
    const updateEffectiveDateInput = document.getElementById('update_effective_date');
    const updateExpiryDateInput = document.getElementById('update_expiry_date');
    const updateInsuranceInput = document.getElementById('update_insurance');
    const updateTenorInput = document.getElementById('update_tenor');
    const updateAmountInput = document.getElementById('update_amount');
    const updateSecurityInput = document.getElementById('update_security');

    // Event listener for the "Update" button
    updateButtons.forEach((updateButton) => {
        updateButton.addEventListener('click', () => {
            const entryId = updateButton.getAttribute('data-id');

            fetch('get_entry.php?id=' + entryId)
                .then((response) => response.json())
                .then((data) => {
                    // Populate the update form with the entry data
                    entryIdInput.value = data.id;
                    updateCompanyName.value = data.company_name;
                    updateClientName.value = data.client_name;
                    updateEmailInput.value = data.email;
                    updatePhoneInput.value = data.phone;
                    updateEffectiveDateInput.value = data.effective_date;
                    updateExpiryDateInput.value = data.expiry_date;
                    updateInsuranceInput.value = data.insurance;
                    updateTenorInput.value = data.tenor;
                    updateAmountInput.value = data.amount;
                    updateSecurityInput.value = data.security;

                    // Open the modal
                    openModal();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        });
    });
</script>

</body>
</html>
