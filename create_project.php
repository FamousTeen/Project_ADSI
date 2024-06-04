<?php
include 'db_connect.php'; 
session_start();

if (isset($_POST["submit"])) {
    // Fetching and sanitizing input
    $projectName = mysqli_real_escape_string($mysqli, $_POST['projectName']);
    $projectDeadline = $_POST['projectDeadline'];
    // $projectProgress = (int)$_POST['projectProgress']; // Ensuring projectProgress is treated as an integer
    $projectProgress = 0;
    $idManager;

    // Fetching idManager from the session (assuming it's stored during login)
    if (isset($_SESSION['idManager'])) {
        $idManager = (int)$_SESSION['idManager']; // Ensuring idManager is treated as an integer
    } else {
        echo "<script>alert('Error: Manager ID not found in session.');</script>";
        exit();
    }

    $startDate = date("Y-m-d");
    $status = 'Not done';

    // Debugging: Output the variables
    echo "<script>console.log('Debug: Variables - startDate: $startDate, projectDeadline: $projectDeadline, projectName: $projectName, projectProgress: $projectProgress, idManager: $idManager, status: $status');</script>";

    // Preparing SQL statement
    $sql = "INSERT INTO project (`startDate`, `Deadline`, `projectName`, `progressBar`, `man`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        // Binding parameters to the SQL statement
        $stmt->bind_param("sssiis", $startDate, $projectDeadline, $projectName, $projectProgress, $idManager, $status);

        // Debugging: Check if statement preparation was successful
        if ($stmt->error) {
            echo "<script>alert('Debug: Error in statement preparation - " . $stmt->error . "');</script>";
        }

        // Executing the statement and checking for success
        if ($stmt->execute()) {
            echo "<script>alert('New project created successfully.');</script>";
            header("Location: createProject_page.php");
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error: " . $mysqli->error . "');</script>";
    }

    mysqli_close($mysqli);
} else {
    // alert "<script>alert('Wrong');</script>";
}
?>

