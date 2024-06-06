<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define class Task
class Task {
    public $idTask;
    public $taskName;
    public $taskDescription;
    public $taskDeadline;
    public $progressTask;
    public $idProject_task;

    // Constructor
    public function __construct($idTask, $taskName, $taskDescription, $taskDeadline, $progressTask, $idProject_task) {
        $this->idTask = $idTask;
        $this->taskName = $taskName;
        $this->taskDescription = $taskDescription;
        $this->taskDeadline = $taskDeadline;
        $this->progressTask = $progressTask;
        $this->idProject_task = $idProject_task;
    }

    // Method to add a task to the project
    public function addTask($mysqli) {
        $taskName = $this->taskName;
        $taskDescription = $this->taskDescription;
        $taskDeadline = $this->taskDeadline;
        $progressTask = $this->progressTask;
        $projectId = $this->idProject_task;

        // Use prepared statements to prevent SQL injection
        $stmt = $mysqli->prepare("INSERT INTO task (taskName, taskDescription, taskDeadline, progressTask, idProject_task) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $taskName, $taskDescription, $taskDeadline, $progressTask, $projectId);

        if ($stmt->execute()) {
            // Update project progress based on accumulated task progress
            $update_query = "UPDATE project SET progressBar = (
                SELECT SUM(progressTask) FROM task WHERE idProject_task = ?
            ) WHERE idProject = ?";
            
            $stmt_update = $mysqli->prepare($update_query);
            $stmt_update->bind_param("ii", $projectId, $projectId);

            if ($stmt_update->execute()) {
                // Check if all tasks' progress sum up to 100% and update project status to "Done"
                $status_query = "SELECT SUM(progressTask) as totalProgress FROM task WHERE idProject_task = ?";
                $stmt_status = $mysqli->prepare($status_query);
                $stmt_status->bind_param("i", $projectId);
                $stmt_status->execute();
                $result = $stmt_status->get_result();
                $row = $result->fetch_assoc();
                $totalProgress = $row['totalProgress'];

                if ($totalProgress >= 100) {
                    $status_update_query = "UPDATE project SET status = 'Done' WHERE idProject = ?";
                    $stmt_status_update = $mysqli->prepare($status_update_query);
                    $stmt_status_update->bind_param("i", $projectId);
                    $stmt_status_update->execute();
                }

                return array('success' => true, 'newProgress' => $totalProgress);
            } else {
                return array('success' => false, 'error' => $stmt_update->error);
            }
        } else {
            return array('success' => false, 'error' => $stmt->error);
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>
