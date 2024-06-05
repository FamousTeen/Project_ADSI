class TaskManager {
    constructor() {
        this.mainContent = document.getElementById('main-content');
    }

    // Method to send AJAX request to PHP script
    async fetchData(action, data = {}) {
        const response = await fetch('your_php_script.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action, ...data })
        });
        return response.json();
    }

    // Method to get tasks and display them
    async getAndDisplayTasks(idProject) {
        const data = await this.fetchData('getTasks', { idProject });
        // Process the data as needed
        data.tasks.forEach(task => {
            let taskCard = document.createElement('div');
            taskCard.classList.add('class-card');
            taskCard.innerHTML = `
                <p><strong>Task Name:</strong> ${task.taskName}</p>
                <p><strong>Task Description:</strong> ${task.taskDescription}</p>
                <p><strong>Deadline:</strong> ${task.taskDeadline}</p>
                <p><strong>Progress:</strong> ${task.progressTask}%</p>
            `;
            this.mainContent.appendChild(taskCard);
        });
    }
}

// Usage
const manager = new TaskManager();
manager.getAndDisplayTasks(selectedProjectId);
