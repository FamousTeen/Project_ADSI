
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('db_connect.php');

if (isset($_POST['addTask'])) {
    $sql = "SELECT idProject, projectName FROM project";
    $result = $mysqli->query($sql);

    if (!$result) {
        echo "Error fetching projects: " . $mysqli->error;
        exit();
    }

    $projects = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $projects[] = $row;
    }

    $projectId = $projects[0]['idProject'];

    $taskName = $_POST['taskName'];
    $taskDescription = $_POST['taskDescription'];
    $progressTask = $_POST['progressTask'];
    $taskDeadline = $_POST['taskDeadline'];

    $taskName = mysqli_real_escape_string($mysqli, $taskName);
    $taskDescription = mysqli_real_escape_string($mysqli, $taskDescription);
    $taskDeadline = mysqli_real_escape_string($mysqli, $taskDeadline);

    $task_query = "INSERT INTO task (`taskName`, `taskDescription`, `progressTask`, `idProject_task`, `taskDeadline`) 
                   VALUES ('$taskName', '$taskDescription', $progressTask, $projectId, '$taskDeadline')";

    if (mysqli_query($mysqli, $task_query)) {
        echo "Task added successfully.";

        $update_query = "UPDATE project SET progressBar = (
            SELECT SUM(progressTask) FROM task WHERE idProject_task = $projectId
        ) WHERE idProject = $projectId";

        if (mysqli_query($mysqli, $update_query)) {
            echo "Project progress updated successfully.";
            header("Location: progressBar.php");
            exit();
        } else {
            echo "Error updating project progress: " . mysqli_error($mysqli);
        }
    } else {
        echo "Error adding task: " . mysqli_error($mysqli);
    }

    mysqli_close($mysqli);
    exit();
}
?>
