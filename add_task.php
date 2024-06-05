<?php
session_start();
include('db_connect.php');

if (isset($_POST['addTask'])) {
    $taskName = $_POST['taskName'];
    $taskDescription = $_POST['taskDescription'];
    $progressTask = $_POST['progressTask'];
    $projectId = $_POST['projectId'];
    $taskDeadline = $_POST['taskDeadline'];

    // Insert the task into the taska table
    $task_query = "INSERT INTO task (taskName, taskDescription, taskDeadline, progressTask, idProject_task) VALUES ('$taskName', '$taskDescription', '$taskDeadline', '$progressTask', '$projectId')";
    

    $stmt = $mysqli->prepare($task_query);
    $stmt->bind_param("sssii", $taskName, $taskDescription, $taskDeadline, $progressTask, $idProject_task);
    
    $res = $stmt->execute();
    
        if ($res) {
            mysqli_close($mysqli);
            header("Location: progressBar.php");
            die();
        } else {
            echo "Tidak dapat menambah permit";
            die();
        }
    
    // if (mysqli_query($mysqli, $task_query)) {
    //     echo "Task added successfully.";

    //     // Update project progress based on accumulated task progress
    //     // $update_query = "UPDATE project SET progressBar = (
    //     //     SELECT SUM(progressTask) FROM task WHERE idProject_task = '$projectId'
    //     // ) WHERE idProject = '$projectId'";

    //     if (mysqli_query($mysqli, $update_query)) {
    //         echo "Project progress updated successfully.";
    //     } else {
    //         echo "Error updating project progress: " . mysqli_error($mysqli);
    //     }

    //     // Debugging
    //     echo "Task progress: $progressTask";
    //     echo "Project ID: $projectId";
    //     echo "Update query: $update_query";
    // } else {
    //     echo "Error adding task: " . mysqli_error($mysqli);
    // }

    // mysqli_close($mysqli);
    // header("Location: createProject_page.php");
    // exit();
}
