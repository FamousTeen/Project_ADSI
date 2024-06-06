<?php
// header('Content-Type: application/json');
include("db_connect.php");
include("progress_class.php");

if (isset($_POST['task'])) {
    $task = new Task(null, $_POST['task'], $_POST['description'], $_POST['deadline'], $_POST['percentage'], $_POST['projectId']);
    $response = $task->addTask($mysqli);

    echo json_encode($response);
    exit; // Don't output anything else
}

session_start();
$dept_name = $_SESSION['dept_name'];

// Fetch manager details
$sql = "SELECT * FROM manager WHERE departmentName = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $dept_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
  $row = $result->fetch_assoc();
  $_SESSION['manager_name'] = $row['managerName'];
}

// Fetch project names and progress values from the database
$query = "SELECT idProject, projectName, progressBar, startDate, Deadline, man FROM project";
$result = mysqli_query($mysqli, $query);

$projects = array();
while ($row = mysqli_fetch_assoc($result)) {
    $projects[] = $row;
}
mysqli_free_result($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Classroom UI</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="header-left">
            <h1>DivRoom</h1>
        </div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <?php if (isset($_SESSION['idManager'])) { ?>
                    <a style="text-decoration: none; color: inherit;" href="createProject_page.php"><li id="permitSide" >My Projects</li></a>
                    <a style="text-decoration: none; color: inherit;" href="progressBar.php"><li id="customSide" class="active">Custom Project Progress</li></a>
                    <a style="text-decoration: none; color: inherit;" href="creditScore.php"><li id="creditSide">Credit score & awards</li></a>
                <?php } else { ?>
                    <a style="text-decoration: none; color: inherit;" href="createProject_page.php"><li id="permitSide" >My Projects</li></a>
                    <a style="text-decoration: none; color: inherit;" href="#"><li id="permitSide" class="active">Permit Request</li></a>
                <?php }?>
            </ul>
        </aside>
        <main class="main-content" id="main-content">
            <div class="progress-bar-container">
                <div id="progress-bar" class="progress-bar"></div>
            </div>

            <form id="addTaskForm" class="task-form">
                <label for="task">Task Name:</label>
                <input type="text" id="task" name="task" placeholder="Task Name" required><br>
                <label for="description">Task Description:</label>
                <input type="text" id="description" name="description" placeholder="Task Description" required><br>
                <label for="deadline">Deadline:</label>
                <input type="date" id="deadline" name="deadline" required><br>
                <label for="percentage">Percentage:</label>
                <input type="number" id="percentage" name="percentage" placeholder="Percentage" required><br>
                <label for="project-select">Select Project:</label>
                <select id="project-select" name="projectId" onchange="switchProject()">
                    <option>--Select Project--</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?php echo htmlspecialchars($project['idProject']); ?>">
                            <?php echo htmlspecialchars($project['projectName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" onclick="addTask()">Add Task</button>
            </form>

            <div id="project-cards-container" class="project-cards-container"></div>
            <div id="project-details-container" class="project-details-container"></div>
            <div id="task-container"></div>
            <script src="script.js"></script>
        </main>
    </div>
</body>
</html>

<style>
/* Add your styles here */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 100vh;
    background-color: #f5f5f5;
}
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #4285F4;
    color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.header-left h1 {
    margin: 0;
}
.container {
    display: flex;
    flex: 1;
}
.sidebar {
    width: 250px;
    background-color: #3c4043;
    color: white;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
}
.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
}
.sidebar ul li {
    padding: 15px;
    cursor: pointer;
    border-radius: 4px;
    margin-bottom: 10px;
    transition: background-color 0.3s ease;
}
.sidebar ul li:hover, .sidebar ul li.active {
    background-color: #5f6368;
}
.main-content {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
}
.progress-bar-container {
    width: 100%;
    background-color: #f3f3f3;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-top: 10px;
    height: 20px;
    position: relative;
}
.progress-bar {
    width: 0;
    height: 20px;
    background-color: green;
    text-align: center;
    color: white;
}
.task-form {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 400px;
    margin-bottom: 20px;
}
.task-form h2 {
    margin-top: 0;
}
.task-form label {
    display: block;
    margin: 10px 0 5px;
}
.task-form input,
.task-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.task-form button {
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 5px;
}
.task-form button:hover {
    background-color: #45a049;
}
.task {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 600px;
    margin-bottom: 20px;
}
.project-details-container {
    display: none;
    margin-top: 20px;
}
.project-details-card {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.project-card {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.project-card h2 {
    margin-top: 0;
}
.project-card p {
    margin-bottom: 10px;
}
.project-card ul {
    padding-left: 20px;
}
.project-card ul li {
    margin-bottom: 5px;
}
</style>

<script>
function addTask() {
    var task = document.getElementById("task").value;
    var description = document.getElementById("description").value;
    var deadline = document.getElementById("deadline").value;
    var percentage = document.getElementById("percentage").value;
    var projectId = document.getElementById("project-select").value;

    if (task === "" || description === "" || deadline === "" || percentage === "" || projectId === "") {
        alert("Please fill in all fields and select a project.");
        return;
    }

    $.ajax({
        url: 'progressBar.php',
        type: 'POST',
        data: {
            task: task,
            description: description,
            deadline: deadline,
            percentage: percentage,
            projectId: projectId
        },
        success: function(response) {
            try {
                var result = JSON.parse(response);
                if (result.success) {
                    updateProgressBar(result.newProgress);
                    alert("Task added successfully!");
                    document.getElementById("addTaskForm").reset();
                } else {
                    alert("Failed to add task. Please try again.");
                }
            } catch (e) {
                console.error('Invalid JSON response', e);
                alert("Unexpected server response. Please try again.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('AJAX call failed', textStatus, errorThrown);
            alert("Error adding task. Please try again.");
        }
    });
}

function updateProgressBar(newProgress) {
    var progressBar = document.getElementById("progress-bar");
    progressBar.style.width = newProgress + "%";
    progressBar.innerHTML = newProgress + "%";

    var projectSelect = document.getElementById("project-select");
    var selectedProjectId = projectSelect.value;
    var selectedProject = projects.find(project => project.idProject == selectedProjectId);
    if (selectedProject) {
        selectedProject.progressBar = newProgress;
    }
}

var projects = <?php echo json_encode($projects); ?>;

function switchProject() {
    var projectSelect = document.getElementById("project-select");
    var selectedProjectId = projectSelect.value;
    var selectedProject = projects.find(project => project.idProject == selectedProjectId);

    var progressBar = document.getElementById("progress-bar");
    if (selectedProject) {
        progressBar.style.width = selectedProject.progressBar + "%";
        progressBar.innerHTML = selectedProject.progressBar + "%";
    } else {
        progressBar.style.width = "0%";
        progressBar.innerHTML = "0%";
    }
}

$( "#permitSide" ).on( "click", function() {
    $( "#permitSide" ).toggleClass("active", true);
    $( "#projectSide" ).toggleClass("active", false);
    $( "#creditSide" ).toggleClass("active", false);
    $( "#customSide" ).toggleClass("active", false);
} );

$( "#projectSide" ).on( "click", function() {
    $( "#projectSide" ).toggleClass("active", true);
    $( "#permitSide" ).toggleClass("active", false);
    $( "#creditSide" ).toggleClass("active", false);
    $( "#customSide" ).toggleClass("active", false);
} );

$( "#creditSide" ).on( "click", function() {
    $( "#creditSide" ).toggleClass("active", true);
    $( "#projectSide" ).toggleClass("active", false);
    $( "#permitSide" ).toggleClass("active", false);
    $( "#customSide" ).toggleClass("active", false);
} );

$( "#customSide" ).on( "click", function() {
    $( "#customSide" ).toggleClass("active", true);
    $( "#projectSide" ).toggleClass("active", false);
    $( "#creditSide" ).toggleClass("active", false);
    $( "#permitSide" ).toggleClass("active", false);
} );

document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.querySelector('.main-content');
    const projectSelect = document.getElementById('project-select');
    const projectDetailsContainer = document.getElementById('project-details-container');

    function displayProjectDetails(project) {
        projectDetailsContainer.innerHTML = `
            <div class="project-details-card">
                <h2>${project.projectName}</h2>
                <p><strong>Start Date:</strong> ${project.startDate}</p>
                <p><strong>Deadline:</strong> ${project.Deadline}</p>
                <p><strong>Progress:</strong> ${project.progressBar}%</p>
                <p><strong>Manager:</strong> ${project.man}</p>
            </div>
        `;
        projectDetailsContainer.style.display = 'block';
    }

    projectSelect.addEventListener('change', () => {
        const selectedProjectId = projectSelect.value;
        const selectedProject = projects.find(project => project.idProject == selectedProjectId);

        if (selectedProject) {
            displayProjectDetails(selectedProject);
        }
    });

    const initialProjectId = projectSelect.value;
    const initialProject = projects.find(project => project.idProject == initialProjectId);
    if (initialProject) {
        displayProjectDetails(initialProject);
    }
});
</script>
