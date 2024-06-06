<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definisi class Project
class Task {
    public $idTask;
    // Properties
    public $taskName;
    public $taskDescription;
    public $taskDeadline;
    public $progressTask;
    public $idProject_task;
    

    public $idEmp;

    // Constructor
    public function __construct($idTask, $taskName, $taskDescription, $taskDeadline, $progressTask, $idProject_task) {
        $this->$idTask = $idTask;
        $this->$taskName = $taskName;
        $this->$taskDescription = $taskDescription;
        $this->$taskDeadline = $taskDeadline;
        $this->$progressTask = $progressTask;
        $this->$idProject_task = $idProject_task;
    }

    // // Method untuk mengatur detail proyek
    // public function inputProjectDetail($projectName, $projectDeadline, $projectManager) {
    //     $idManager= null;
    //     // Fetching idManager from the session (assuming it's stored during login)
    // if (isset($_SESSION['idManager'])) {
    //     $idManager = (int)$_SESSION['idManager']; // Ensuring idManager is treated as an integer
    // } else {
    //     echo "<script>alert('Error: Manager ID not found in session.');</script>";
    //     exit();
    // }
    //     $this->projectName = $_POST['projectName'];
    //     $this->projectDeadline = $_POST['projectDeadline'];
    //     $this->projectManager = $idManager;
    // }

    // Method untuk membuat proyek
    
    // Method untuk menambahkan anggota tim ke proyek
    
    // Method untuk menambahkan tugas ke proyek
    public function addTask($taskName, $taskDescription, $taskDeadline, $progressTask, $idProject_task) {       
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
        
        if (mysqli_query($mysqli, $task_query)) {
            echo "Task added successfully.";

            // Update project progress based on accumulated task progress
            $update_query = "UPDATE project SET progressBar = (
                SELECT SUM(progressTask) FROM task WHERE idProject_task = '$projectId'
            ) WHERE idProject = '$projectId'";

            if (mysqli_query($mysqli, $update_query)) {
                echo "Project progress updated successfully.";

                // Debugging: Output the progress value
                echo "Progress Task Value: $progressTask"; // Add this line

                // Check if progress is 100% and update project status to "Done"
                if ($progressTask == 100) {
                    $status_update_query = "UPDATE project SET status = 'Done' WHERE idProject = '$projectId'";
                    if (mysqli_query($mysqli, $status_update_query)) {
                        echo "Project status updated to Done.";
                    } else {
                        echo "Error updating project status: " . mysqli_error($mysqli);
                    }
                }
            } else {
                echo "Error updating project progress: " . mysqli_error($mysqli);
            }
        } else {
            echo "Error adding task: " . mysqli_error($mysqli);
        }

        mysqli_close($mysqli);
        header("Location: createProject_page.php");
        exit();
    }
        
    }
}
?>
