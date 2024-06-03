<?php
include 'db_connect.php'; // Make sure this file contains the necessary database connection code

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'], $_POST['department'], $_POST['phone'], $_POST['password'], $_POST['role'], $_POST['cpassword'])) {
    // Collect and sanitize input data
    $username = mysqli_real_escape_string($mysqli, $_POST['username']);
    $department = mysqli_real_escape_string($mysqli, $_POST['department']);
    $phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);
    $cpassword = mysqli_real_escape_string($mysqli, $_POST['cpassword']);
    $role = mysqli_real_escape_string($mysqli, $_POST['role']);
    
    // Check if password and confirmation password match
    if ($password != $cpassword) {
        echo "<script>alert('Error: Passwords do not match.');</script>";
        die();
    }

    // Insert data into the appropriate table based on the role
    if ($role == 'manager') {
        $sql = "INSERT INTO manager (managerName, departmentName, no_telp, status, password) VALUES ('$username', '$department', '$phone', 'onDuty', '$password')";
    } else if ($role == 'employee') {
        $sql = "INSERT INTO employee (empName, departmentName, no_telp, credit_score, status, password) VALUES ('$username', '$department', '$phone', 0, 'idle', '$password')";
    } else {
        echo "<script>alert('Error: Invalid role selected.');</script>";
        die();
    }
    
    // Execute the query
    if (mysqli_query($mysqli, $sql)) {
        echo "<script>alert('New record created successfully. Redirecting to login page...');</script>";
        echo "<script>window.location.replace('loginPage.php');</script>";
        exit(); // Added exit() after header redirect to prevent further execution
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
    }
    
    // Close the database connection
    mysqli_close($mysqli);
} else {
    echo "<script>alert('Error: Invalid request or missing form data.');</script>";
}
?>

