<?php 
include("db_connect.php");

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

// Fetch permit details
$idEmp = $_SESSION['idEmp'];
$sql = "SELECT * FROM permit WHERE emp = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $idEmp);
$stmt->execute();
$result = $stmt->get_result();

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'title' => $row['permitTitle'],
        'desc' => $row['description'],
        'date' => $row['permitDate'],
        'status' => $row['status']
    );
}

// Fetch project details
$sql = "SELECT idProject, projectName FROM project";
$result = $mysqli->query($sql);
$row2 = mysqli_fetch_assoc($result);
$idProject2 = $row2['idProject'];
$projects = array();
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

$sql = "SELECT * FROM task WHERE idProject_task = $idProject2";
$result2 = $mysqli->query($sql);
$projects2 = array();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Classroom UI</title>
    <link rel="stylesheet" href="progressBar.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

</head>
<body>
    <header>
        <div class="header-left">
            <h1>DivRoom</h1>
        </div>
        <div class="header-right">
            <button id="createProjectBtn">Request New Permit</button>
        </div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <a style="text-decoration: none; color: inherit;" href="createProject_page"><li id="permitSide" >My Projects</li></a>
                <a style="text-decoration: none; color: inherit;" href="createPermitReq.php"><li id="permitSide" >Permit Request</li></a>
                <a style="text-decoration: none; color: inherit;" href="#"><li id="customSide" class="active">Custom Project Progress</li></a>
                <a style="text-decoration: none; color: inherit;" href="creditScore.php"><li id="creditSide">Credit score & awards</li></a>
            </ul>
        </aside>
        <main class="main-content" id="main-content">

        <div class="progress-bar-container">
            <div class="progress-bar background" id="background-progress"></div>
            <div class="progress-bar approved" id="approved-progress" style="width: 0%;"></div>
            <div class="progress-bar not-approved" id="not-approved-progress" style="width: 0%;"></div>
        </div>

        <div class="project-selection">
            <label for="project-select">Select Project:</label>
            <select id="project-select" onchange="switchProject()">
                <option value="">--Select Project--</option>
                <?php 
                include("db_connect.php");
                $result = mysqli_query($mysqli, "SELECT idProject, projectName FROM project");
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <option value="<?php echo $row['idProject']; ?>"><?php echo $row['projectName']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="progress-container">
            <div class="progress-bar" id="progress-bar"></div>
        </div>

        <div class="task-form" id="task-form">
            <h2>Add Task</h2>
            <form id="addTaskForm" method="POST" action="addTask.php">
                <label for="task">Task:</label>
                <input type="text" id="task" name="taskName" required>
                

                <label for="description">Description:</label>
                <textarea id="description" name="taskDescription"></textarea>

                <label for="taskDeadline">Deadline:</label>
                <input type="date" id="taskDeadline" name="taskDeadline" required>

                <label for="progressTask">Progress:</label>
                <input type="number" id="progressTask" name="progressTask" min="0" max="100" required>

                <input type="hidden" id="idProject_task" name="idProject_task" value="">

                <button type="submit" name="addtask">Add Task</button>
            </form>
        </div>

        </main>
    </div>
</body>
</html>



</body>
</html>

    <div id="task-container"></div>
    <script src="progressBarScript.js"></script>
    <!-- Create Project Modal -->
    

    
</body>
</html>




<!-- Path: styles.css -->
<style>
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

.header-right {
    display: flex;
    align-items: center;
}

.header-right input {
    padding: 5px;
    margin-right: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

.header-right button {
    padding: 5px 10px;
    background-color: white;
    color: #4285F4;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
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

/* Card styles */
/* Card styles */
.class-card {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-left: 5px solid #4285F4; /* Add a colored border for emphasis */
}

.class-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.class-card .project-name {
    background-color: #0f9d58;
    color: #4285F4; /* Project name color */
    font-size: 24px; /* Adjust font size as needed */
}

.class-card .label {
    font-weight: bold;
    color: #666; /* Label color */
}

.class-card .project-deadline {
    color: #ea4335; /* Deadline color */
}

.class-card .project-progress {
    color: #0f9d58; /* Progress color */
}

.class-card .file-types {
    color: #fbbc05; /* File types color */
}

.class-card button {
    padding: 5px 10px;
    background-color: #4285F4;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.class-card button:hover {
    background-color: #357ae8;
}


/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-btn:hover,
.close-btn:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

form label {
    display: block;
    margin: 10px 0 5px;
}

form input,
form select,
form button {
    width: 100%;
    padding: 10px;
    margin: 5px 0 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

form button {
    background-color: #4285F4;
    color: white;
    cursor: pointer;
}

form button:hover {
    background-color: #357ae8;
}

#newMemberForm.hidden {
    display: none;
}

#memberList {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

#memberList th,
#memberList td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

#memberList th {
    background-color: #f2f2f2;
}

#memberList {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

#tasklist th,
#tasklist td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

#tasklist th {
    background-color: #f2f2f2;
}
#newTaskForm.hidden {
    display: none;
}


/* Add this style for select menu */
form select {
    width: calc(100% - 22px); /* Adjust width to fit content */
    padding-right: 22px; /* Add space for dropdown arrow */
    background-image: url('arrow-down.svg'); /* Path to dropdown arrow icon */
    background-repeat: no-repeat;
    background-position: right center;
    appearance: none; /* Remove default appearance */
    -webkit-appearance: none; /* Safari and Chrome */
    -moz-appearance: none; /* Firefox */
    border-radius: 4px;
}


</style>

<script>
         <?php 
            $permitIndex = 0;
            foreach ($result2 as $row) {
                $projects2[$permitIndex] = array(
                'taskName' => $row['taskName'],
                'taskDescription' => $row['taskDescription'],
                'taskDeadline' => $row['taskDeadline'],
                'progressTask' => $row['progressTask']
                );
            ?> 
            var mainContent = document.getElementById('main-content');
            var permitCard = document.createElement('div');
            permitCard.classList.add('class-card');
            permitCard.innerHTML += 
                `<p><strong>Task Name:</strong><?php echo $projects2[$permitIndex]['taskName'] ?></p>
                        <p><strong>Task Description:</strong><?php echo $projects2[$permitIndex]['taskDescription']?></p>
                        <p><strong>Deadline:</strong> <?php echo $projects2[$permitIndex]['taskDeadline']?></p>
                        <p><strong>Progress:</strong> <?php echo $projects2[$permitIndex]['progressTask']?>%</p>
            `;
            mainContent.appendChild(permitCard);
        <?php  $permitIndex +=1; };  ?>


        function switchProject() {
            var selectedProjectId = document.getElementById('project-select').value;
            document.getElementById('idProject_task').value = selectedProjectId;
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
    const createProjectBtn = document.getElementById('createProjectBtn');
    const modal = document.getElementById('createProjectModal');
    const closeBtn = document.querySelector('.close-btn');
    const createProjectForm = document.getElementById('createProjectForm');
    const mainContent = document.querySelector('.main-content');


    createProjectBtn.onclick = () => {
        modal.style.display = 'block';
    }

    closeBtn.onclick = () => {
        modal.style.display = 'none';
        newTaskForm.classList.add('hidden'); // Close the add task form when closing the modal
    }

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
            newTaskForm.classList.add('hidden'); // Close the add task form when clicking outside the modal
        }
    }

    const permitDate = document.getElementById('permitDate').value;
    
    const currentDate = new Date();
    const selectedDate = new Date(permitDate);

    if (permitTitle && permitDate) {
        if (selectedDate < currentDate) {
            alert('Please select a deadline after the current date.');
            return; // Exit the function if the deadline is before the current date
        }

        const permitCard = document.createElement('div');
        permitCard.classList.add('class-card');
        permitCard.innerHTML = `
            <h2>${permitTitle}</h2>
            <p>Description: ${permitDesc}</p>
            <p>Deadline: ${permitDate}</p>
            <button>See Permit Detail</button>
        `;
        mainContent.appendChild(permitCard);
    } else {
        alert('Please fill in all required fields');
    }

});

    </script>