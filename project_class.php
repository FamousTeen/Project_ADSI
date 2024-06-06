<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definisi class Project
class Project {
    public $idProject;
    // Properties
    public $projectDetail;
    public $startDate;
    public $progressBar;
    public $status;
    public $projectDeadline;
    public $projectManager;
    public $man;

    public $idEmp;

    // Constructor
    public function __construct($idProject, $startDate, $projectDeadline, $projectDetail, $progressBar, $man, $status) {
        $this->$idProject = $idProject;
        $this->$projectDetail = $projectDetail;
        $this->$startDate = $startDate;
        $this->$projectDeadline = $projectDeadline;
        $this->$progressBar = $progressBar;
        $this->$status = $status;
        $this->$man = $man;
        $this->$idEmp = $idEmp;
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
    public function createProject($startDate, $projectDeadline, $projectDetail, $progressBar) {
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
    }

    // Method untuk menambahkan anggota tim ke proyek
    public function addEmployee() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        session_start();
        include('db_connect.php');

        if (isset($_POST['addEmployee'])) {
            $employeeId = $_POST['employeeId'];
            $projectId = $_POST['projectId'];

            // Insert the employee into the project (assuming you have a project_employee table)
            $query = "INSERT INTO employeeProject (idEmp, idProject) VALUES ('$employeeId', '$projectId')";
            
            if (mysqli_query($mysqli, $query)) {
                echo "Employee added successfully.";
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($mysqli);
            }

            mysqli_close($mysqli);
            header("Location: createProject_page.php");
            exit();
        }

    }

    // Method untuk menambahkan tugas ke proyek
    public function addTask() {       
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
