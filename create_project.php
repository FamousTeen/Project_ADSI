<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include 'db_connect.php';

    // Check if the connection is established
    if (!$pdo) {
        die("Could not connect to the database.");
    }

    // Get project data from the form
    $projectName = $_POST['projectName'] ?? '';
    $projectDeadline = $_POST['projectDeadline'] ?? '';
    $projectProgress = $_POST['projectProgress'] ?? 0;
    $fileTypes = $_POST['fileTypes'] ?? [];

    // Combine file types with project details
    $projectDetail = $projectName . ' - File Types: ' . implode(', ', $fileTypes);

    // Get selected employee IDs
    $employeeList = $_POST['employeeList'] ?? []; // This will be an array of selected employee IDs

    // Validate and sanitize the input data as needed
    if (empty($projectName) || empty($projectDeadline) || empty($employeeList)) {
        echo "Required fields are missing.";
        exit();
    }

    try {
        // Insert project data into the projects table
        $sql = "INSERT INTO projects (startDate, Deadline, projectDetail, progressBar, empList, man, status) VALUES (NOW(), ?, ?, ?, ?, 1, 'In Progress')";
        $stmt = $pdo->prepare($sql);
        
        // Debug: Check if the statement was prepared
        if (!$stmt) {
            die("Failed to prepare the SQL statement.");
        }

        $success = $stmt->execute([$projectDeadline, $projectDetail, $projectProgress, implode(',', $employeeList)]);

        // Check if the insert was successful
        if ($success) {
            // Redirect or display success message
            header("Location: createProject_page.php");
            exit();
        } else {
            echo "Failed to insert data into the database.";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "<script>console.error('Database error: " . addslashes($e->getMessage()) . "');</script>";
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
} else {
    // Handle invalid form submission
    echo "Invalid request";
}
?>
