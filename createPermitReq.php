
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
            <button id="createProjectBtn">Create Project</button>
        </div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <li id="projectSide" class="active">Projects</li>
                <li id="permitSide">Permit</li>
                <li id="customSide">Custom Project Progress</li>
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
            <h2>Create New Project</h2>
            <form id="createProjectForm">
                <label for="projectName">Project Name:</label>
                <input type="text" id="projectName" name="projectName" >
                
                <label for="projectDeadline">Project Deadline:</label>
                <input type="date" id="projectDeadline" name="projectDeadline" >

                <!-- Add Team Members Section -->
            <label for="task">Add Task:</label>
            <div id="task">
                <button type="button" id="addTaskFormButton">+ Add Task</button>
            </div>

            <div id="newTaskForm" class="hidden">
                <label for="taskName">Name:</label>
                <input type="text" id="taskNameId"  >
                
                <label for="taskDescription">Description:</label>
                <input type="text" id="taskDescription">

                <label for="taskProgress">Progress:</label>
                <input type="number" id="taskProgress" min="0" max="100">
                

                <button type="button" id="addTaskButton">Add</button>
            </div>

            <table id="tasklist">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Progress</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

                
                <label for="projectProgress">Project Progress:</label>
                <input type="number" id="projectProgress" name="projectProgress" min="0" max="100" required>
                
                <label for="teamMembers">Add Team Members:</label>
                <div id="teamMembers">
                    <button type="button" id="addMemberFormBtn">+ Add Member</button>
                </div>
                
                <div id="newMemberForm" class="hidden">
                    <label for="memberName">Name:</label>
                    <input type="text" id="memberNameId"  >
                    
                    <label for="memberEmail">Email:</label>
                    <input type="email" id="memberEmail"  >
                    
                    
                    <button type="button" id="addMemberBtn">Add</button>
                </div>
                
                <table id="memberList">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                
                <label for="fileTypes">File Submission Type:</label>
<select id="fileTypes" name="fileTypes" required multiple>
    <option value="pdf">PDF</option>
    <option value="doc">DOC</option>
    <option value="ppt">PPT</option>
    <option value="xls">XLS</option>
    <option value="jpg">JPG</option>
    <option value="png">PNG</option>
</select>


                
                <button type="submit" id="saveProjectButton">Create Project</button>
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
    const addMemberFormBtn = document.getElementById('addMemberFormBtn');
    const newMemberForm = document.getElementById('newMemberForm');
    const addMemberBtn = document.getElementById('addMemberBtn');
    const memberList = document.getElementById('memberList').querySelector('tbody');
    const mainContent = document.querySelector('.main-content');

    const addTaskFormButton = document.getElementById('addTaskFormButton');
    const newTaskForm = document.getElementById('newTaskForm');
    const addTaskButton = document.getElementById('addTaskButton');
    const tasklist = document.getElementById('tasklist').querySelector('tbody');

    

    addTaskButton.onclick = () => {
        const taskname = document.getElementById('taskNameId').value;
        const taskDescription = document.getElementById('taskDescription').value;
        const taskProgress = parseInt(document.getElementById('taskProgress').value);

        if (taskname && taskDescription && !isNaN(taskProgress) && taskProgress >= 0 && taskProgress <= 100) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${taskname}</td>
                <td>${taskDescription}</td>
                <td>${taskProgress}%</td>
                <td><button type="button" class="remove-task">Remove</button></td>
            `;
            tasklist.appendChild(row);

            row.querySelector('.remove-task').onclick = () => {
                row.remove();
                updateProjectProgress(); // Update project progress when a task is removed
            }

            updateProjectProgress(); // Update project progress when a new task is added

            // Reset form
            document.getElementById('taskNameId').value = '';
            document.getElementById('taskDescription').value = '';
            document.getElementById('taskProgress').value = '';
            newTaskForm.classList.add('hidden'); // Close the add task form
        } else {
            alert('Please fill in all fields correctly');
        }
    }

    function updateProjectProgress() {
    const tasks = document.querySelectorAll('#tasklist tbody tr');
    let totalProgress = 0;
    tasks.forEach(task => {
        const progressText = task.querySelector('td:nth-child(3)').textContent;
        const progress = parseInt(progressText.replace('%', ''));
        if (!isNaN(progress) && progress >= 0 && progress <= 100) {
            totalProgress += progress;
        }
    });
    const projectProgressInput = document.getElementById('projectProgress');
    projectProgressInput.value = totalProgress; // Update project progress with accumulated progress
}


    addTaskFormButton.onclick = () => {
        newTaskForm.classList.toggle('hidden');
    }


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

    addMemberFormBtn.onclick = () => {
        newMemberForm.classList.toggle('hidden');
    }

    addMemberBtn.onclick = () => {
        const name = document.getElementById('memberNameId').value;
        const email = document.getElementById('memberEmail').value;

        if (name && email) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${name}</td>
                <td>${email}</td>
                <td><button type="button" class="remove-member">Remove</button></td>
            `;
            memberList.appendChild(row);

            row.querySelector('.remove-member').onclick = () => {
                row.remove();
            }

            // Reset form
            document.getElementById('memberNameId').value = '';
            document.getElementById('memberEmail').value = '';
            newMemberForm.classList.add('hidden'); // Close the add member form
        } else {
            alert('Please fill in all fields');
        }
    }

    createProjectForm.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent form submission

    const projectName = document.getElementById('projectName').value;
    const projectDeadline = document.getElementById('projectDeadline').value;
    const projectProgress = document.getElementById('projectProgress').value;
    const fileTypes = Array.from(document.getElementById('fileTypes').selectedOptions).map(option => option.value);
    
    const currentDate = new Date();
    const selectedDeadline = new Date(projectDeadline);

    if (projectName && projectDeadline && projectProgress && fileTypes.length > 0) {
        if (selectedDeadline < currentDate) {
            alert('Please select a deadline after the current date.');
            return; // Exit the function if the deadline is before the current date
        }

        const projectCard = document.createElement('div');
        projectCard.classList.add('class-card');
        projectCard.innerHTML = `
            <h2>${projectName}</h2>
            <p>Deadline: ${projectDeadline}</p>
            <p>Progress: ${projectProgress}%</p>
            <p>File Types: ${fileTypes.join(', ')}</p>
            <button>Edit Project</button>
        `;
        mainContent.appendChild(projectCard);

        // Reset form and close modal
        createProjectForm.reset();
        modal.style.display = 'none';
    } else {
        alert('Please fill in all required fields');
    }
});


});

    </script>