<?php
include 'db_connect.php';
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['projectName'], $_POST['projectDeadline'], $_POST['projectProgress'], $_POST['idManager'])) {
    // Collect input data
    $projectName = mysqli_real_escape_string($mysqli, $_POST['projectName']);
    $projectDeadline = mysqli_real_escape_string($mysqli, $_POST['projectDeadline']);
    $projectProgress = mysqli_real_escape_string($mysqli, $_POST['projectProgress']);
    $idManager = mysqli_real_escape_string($mysqli, $_POST['idManager']);

    // Echo to check if form data is received
    echo "Project Name: $projectName, Deadline: $projectDeadline, Progress: $projectProgress, ID Manager: $idManager";

    // Your database insertion code here...
    // Get the current date for startDate
    $startDate = date("Y-m-d");

    // Get the manager's ID from session
    $idManager = mysqli_real_escape_string($mysqli, $_POST['idManager']);
    
    // Set default status to 'Not done'
    $status = 'Not done';

    // Prepare and bind the SQL statement
    $sql = "INSERT INTO project (startDate, Deadline, projectName, progressBar, man, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssssss", $startDate, $projectDeadline, $projectName, $projectProgress, $idManager, $status);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "New project created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $mysqli->close();
} else {
    echo "Invalid request or missing form data.";
}
?>
