let tasks = [];

function updateProgress() {
    let totalApprovedPercentage = 0;
    let totalNotApprovedPercentage = 0;

    tasks.forEach(task => {
        if (task.status === 'approved') {
            totalApprovedPercentage += task.percent;
        } else if (task.status === 'not-approved') {
            totalNotApprovedPercentage += task.percent;
        }
    });

    const totalPercentage = totalApprovedPercentage + totalNotApprovedPercentage;

    const notApprovedProgress = document.getElementById('not-approved-progress');

    if (totalPercentage >= 100 || notApprovedProgress.style.width === '100%') {
        notApprovedProgress.style.width = '100%';
    } else {
        notApprovedProgress.style.width = totalNotApprovedPercentage + '%';
    }

    const approvedProgress = document.getElementById('approved-progress');

    if (totalPercentage >= 100) {
        approvedProgress.style.width = '100%';
    } else {
        approvedProgress.style.width = totalApprovedPercentage + '%';
    }

    document.getElementById('background-progress').style.width = '100%';
}

function filterTasks(filter) {
    const taskList = document.getElementById('task-list');
    taskList.innerHTML = '';

    tasks.forEach(task => {
        if (filter === task.status || (filter === 'unfinished' && task.status === 'unfinished')) {
            const taskItem = document.createElement('li');
            taskItem.className = `task ${task.status}`;
            taskItem.innerHTML = `
                <div class="task-info">
                    ${task.name}
                    ${task.status === 'unfinished' ? `
                        <button onclick="finishTask(${task.id})">Finish</button>
                    ` : task.status === 'not-approved' ? `
                        <button onclick="approveTask(${task.id})">Approve</button>
                        <button class="deny" onclick="denyTask(${task.id})">Deny</button>
                    ` : ''}
                </div>
                <p class="description">${task.description}</p>
                <p class="deadline">Deadline: ${task.deadline}</p>
                <p class="percentage">Percentage: ${task.percent}%</p>
            `;
            taskList.appendChild(taskItem);
        }
    });
}

function addTask() {
    const taskInput = document.getElementById('new-task-input');
    const taskDesc = document.getElementById('new-task-desc');
    const taskDeadline = document.getElementById('new-task-deadline');
    const taskPercent = document.getElementById('new-task-percent');
    const taskName = taskInput.value.trim();
    const taskDescription = taskDesc.value.trim();
    const taskDeadlineValue = taskDeadline.value;
    const taskPercentValue = parseInt(taskPercent.value);
    
    if (taskName && taskPercentValue >= 0 && taskPercentValue <= 100) {
        const newTask = {
            id: tasks.length,
            name: taskName,
            description: taskDescription,
            status: 'unfinished',
            deadline: taskDeadlineValue,
            percent: taskPercentValue
        };
        tasks.push(newTask);
        taskInput.value = '';
        taskDesc.value = '';
        taskDeadline.value = '';
        taskPercent.value = '';
        updateProgress();
        filterTasks('unfinished');
    } else {
        alert("Please enter a valid percentage between 0 and 100.");
    }
}

function finishTask(taskId) {
    tasks = tasks.map(task => task.id === taskId ? { ...task, status: 'not-approved' } : task);
    updateProgress();
    filterTasks('unfinished');
}

function approveTask(taskId) {
    tasks = tasks.map(task => task.id === taskId ? { ...task, status: 'approved' } : task);
    updateProgress();
    filterTasks('not-approved');
}

function denyTask(taskId) {
    tasks = tasks.filter(task => task.id !== taskId);
    updateProgress();
    filterTasks('not-approved');
}

document.addEventListener('DOMContentLoaded', () => filterTasks('approved'));
