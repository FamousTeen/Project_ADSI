let projects = {
    'Project 1': { totalPercentage: 0, tasks: [] },
    'Project 2': { totalPercentage: 0, tasks: [] },
    'Project 3': { totalPercentage: 0, tasks: [] }
};
let currentProject = '';

function switchProject() {
    const projectSelect = document.getElementById('project-select');
    currentProject = projectSelect.value;

    if (currentProject) {
        updateUI();
        document.getElementById('task-form').style.display = 'block';
    } else {
        document.getElementById('task-form').style.display = 'none';
    }
}

function updateUI() {
    let progressBar = document.getElementById('progress-bar');
    progressBar.style.width = projects[currentProject].totalPercentage + '%';

    let taskContainer = document.getElementById('task-container');
    taskContainer.innerHTML = '';
    projects[currentProject].tasks.forEach(task => {
        let taskElement = document.createElement('div');
        taskElement.className = 'task';
        taskElement.innerHTML = `
            <h3>${task.task}</h3>
            <p>${task.description}</p>
            <p><strong>Deadline:</strong> ${task.deadline}</p>
            <p><strong>Percentage:</strong> ${task.percentage}%</p>
        `;
        taskContainer.prepend(taskElement);
    });
}

function addTask() {
    let task = document.getElementById('task').value;
    let description = document.getElementById('description').value;
    let deadline = document.getElementById('deadline').value;
    let percentage = parseInt(document.getElementById('percentage').value);

    // Update the total percentage for the current project
    projects[currentProject].totalPercentage += percentage;
    if (projects[currentProject].totalPercentage > 100) {
        projects[currentProject].totalPercentage = 100; // Cap the total percentage at 100%
    }

    // Add the task to the current project
    projects[currentProject].tasks.push({ task, description, deadline, percentage });

    // Update the UI to reflect the new state
    updateUI();

    // Clear form fields
    document.getElementById('task').value = '';
    document.getElementById('description').value = '';
    document.getElementById('deadline').value = '';
    document.getElementById('percentage').value = '';
}