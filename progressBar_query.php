<?php
include("db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST['task'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $percentage = $_POST['percentage'];
    $projectId = $_POST['projectId'];

    // Insert the new task into the database
    $stmt = $mysqli->prepare("INSERT INTO task (taskName, taskDescription, taskDeadline, progressTask, idProject_task) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $task, $description, $deadline, $percentage, $projectId);

    if ($stmt->execute()) {
        // Get the current progress of the project from the database
        $stmt = $mysqli->prepare("SELECT progressBar FROM project WHERE idProject = ?");
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $currentProgress = $row['progressBar'];

        // Calculate the new progress by adding the new task's percentage to the current progress
        $newProgress = $currentProgress + $percentage;

        // Update the project's progress in the database
        $stmt = $mysqli->prepare("UPDATE project SET progressBar = ? WHERE idProject = ?");
        $stmt->bind_param("di", $newProgress, $projectId);
        $stmt->execute();

        echo json_encode(['success' => true, 'newProgress' => $newProgress]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
