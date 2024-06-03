<?php 
include("db_connect.php");

session_start();
$dept_name = $_SESSION['dept_name'];

$sql = "SELECT * FROM manager WHERE departmentName ='$dept_name'";
$result = mysqli_query($mysqli, $sql);

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_assoc($result);

  $_SESSION['manager_name'] = $row['managerName'];
}

$idEmp = $_SESSION['idEmp'];
$sql = "SELECT * FROM permit WHERE emp = $idEmp";
$result = mysqli_query($mysqli, $sql);

$data = array();

$index = 0;
$permitIndex = 0;

foreach ($result as $row) {
    $permitIndex +=1;
    $data[] = array(
        'title' => $row['permitTitle'],
        'desc' => $row['description'],
        'date' => $row['permitDate'],
        'status' => $row['status']
    );
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Classroom UI</title>
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
                <a style="text-decoration: none; color: inherit;" href="createProject.html"><li id="projectSide">My Project</li></a>
                <li id="permitSide" class="active">Permit Request</li>
                <a style="text-decoration: none; color: inherit;" href="createProject.html"><li id="customSide">Custom Project Progress</li></a>
                <li id="creditSide">Credit score & awards</li>
            </ul>
        </aside>
        <main class="main-content">
            <!-- Project Cards will be added here -->
        </main>
    </div>

    <!-- Create Project Modal -->
    <div id="createProjectModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Request new permit</h2>
            <p>Manager name: <?php echo $_SESSION['manager_name']?></p>
            <form action="addPermit.php" id="createProjectForm" method="POST">
                <label for="permitTitle">Permit Title:</label>
                <input style="width: 97%;" type="text" id="permitTitle" name="permitTitle" >
                
                <label for="permitDesc">Permit Description:</label>
                <textarea style="width: 100%;" placeholder="Enter permit description" id="permitDesc" name="permitDesc"></textarea>

                <label for="permitDate">Permit Date:</label>
                <input style="width: 97%;" type="date" id="permitDate" name="permitDate" >
                
                <button name="Send" type="submit" id="saveProjectButton">Send Permit</button>
            </form>
        </div>
    </div>

    
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