<?php
include("db_connect.php");

if (isset($_POST['projectId'])) {
    $projectId = $_POST['projectId'];

    $stmt = $mysqli->prepare("SELECT taskName, taskDescription, taskDeadline, progressTask FROM tasks WHERE projectId = ?");
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = array();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    echo json_encode($tasks);
} else {
    echo json_encode([]);
}
?>
