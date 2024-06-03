
   
document.addEventListener('DOMContentLoaded', () => {
    
    const permitSide = document.getElementById('permitSide');
    const projectSide = document.getElementById('projectSide');
    const creditSide = document.getElementById('creditSide');
    const customSide = document.getElementById('customSide');
    const createProjectBtn = document.getElementById('createProjectBtn');
    const modal = document.getElementById('createProjectModal');
    const closeBtn = document.querySelector('.close-btn');
    const createProjectForm = document.getElementById('createProjectForm');
    const addMemberBtn = document.getElementById('addMemberBtn');
    const memberList = document.getElementById('memberList').querySelector('tbody');
    const mainContent = document.querySelector('.main-content');
    const employeeList = document.getElementById('employeeList');

    const addTaskFormButton = document.getElementById('addTaskFormButton');
    const newTaskForm = document.getElementById('newTaskForm');
    const addTaskButton = document.getElementById('addTaskButton');
    const tasklist = document.getElementById('tasklist').querySelector('tbody');

    permitSide.addEventListener('click', () => {
        permitSide.classList.add('active');
        projectSide.classList.remove('active');
        creditSide.classList.remove('active');
        customSide.classList.remove('active');
    });

    projectSide.addEventListener('click', () => {
        projectSide.classList.add('active');
        permitSide.classList.remove('active');
        creditSide.classList.remove('active');
        customSide.classList.remove('active');
    });

    creditSide.addEventListener('click', () => {
        creditSide.classList.add('active');
        projectSide.classList.remove('active');
        permitSide.classList.remove('active');
        customSide.classList.remove('active');
    });

    customSide.addEventListener('click', () => {
        customSide.classList.add('active');
        projectSide.classList.remove('active');
        creditSide.classList.remove('active');
        permitSide.classList.remove('active');
    });

    function showNewMemberForm() {
    document.getElementById('newMemberForm').classList.remove('hidden');
    // Set the required attribute dynamically when showing the form
    document.getElementById('employeeList').required = true;
}

// Similarly, if you have a function to hide the newMemberForm
function hideNewMemberForm() {
    document.getElementById('newMemberForm').classList.add('hidden');
    // Remove the required attribute when hiding the form
    document.getElementById('employeeList').required = false;
}

    fetch('get_employee.php')
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                employeeList.innerHTML = '';
                data.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = employee.idEmp;
                    option.textContent = `${employee.idEmp} - ${employee.empName} - ${employee.departmentName}`;
                    employeeList.appendChild(option);
                });
            } else {
                console.error('No employees found');
            }
        })
        .catch(error => {
            console.error('Error fetching employee data:', error);
        });

    addMemberBtn.onclick = () => {
        const selectedOption = employeeList.options[employeeList.selectedIndex];
        const employeeId = selectedOption.value;
        const employeeName = selectedOption.textContent.split(' - ')[1];
        const departmentName = selectedOption.textContent.split(' - ')[2];

        if (employeeId) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${employeeId}</td>
                <td>${employeeName}</td>
                <td>${departmentName}</td>
                <td><button type="button" class="remove-member">Remove</button></td>
            `;
            memberList.appendChild(row);

            row.querySelector('.remove-member').onclick = () => {
                row.remove();
            }

            employeeList.value = '';
            newMemberForm.classList.add('hidden');
        } else {
            alert('Please select an employee from the list.');
        }
    };

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
                updateProjectProgress();
            }

            updateProjectProgress();

            document.getElementById('taskNameId').value = '';
            document.getElementById('taskDescription').value = '';
            document.getElementById('taskProgress').value = '';
            newTaskForm.classList.add('hidden');
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
        projectProgressInput.value = totalProgress;
    }

    addTaskFormButton.onclick = () => {
        newTaskForm.classList.toggle('hidden');
    }

    createProjectBtn.onclick = () => {
        modal.style.display = 'block';
    }

    closeBtn.onclick = () => {
        modal.style.display = 'none';
        newTaskForm.classList.add('hidden');
    }

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
            newTaskForm.classList.add('hidden');
        }
    }

    addMemberFormBtn.onclick = () => {
        newMemberForm.classList.toggle('hidden');
    }

    createProjectForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const projectName = document.getElementById('projectName').value;
        const projectDeadline = document.getElementById('projectDeadline').value;
        const projectProgress = document.getElementById('projectProgress').value;
        const fileTypes = Array.from(document.getElementById('fileTypes').selectedOptions).map(option => option.value);
        
        const currentDate = new Date();
        const selectedDeadline = new Date(projectDeadline);

        if (projectName && projectDeadline && projectProgress && fileTypes.length > 0) {
            if (selectedDeadline < currentDate) {
                alert('Please select a deadline after the current date.');
                return;
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

            createProjectForm.reset();
            modal.style.display = 'none';
        } else {
            alert('Please fill in all required fields');
        }

        
    });
});
