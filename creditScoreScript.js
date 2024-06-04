document.addEventListener('DOMContentLoaded', loadEmployeeData);

let currentEditEmployee = null;

function getPerformanceRating(creditScore) {
    if (creditScore >= 90) {
        return 'Excellent';
    } else if (creditScore >= 75) {
        return 'Good';
    } else if (creditScore >= 50) {
        return 'Average';
    } else if (creditScore >= 25) {
        return 'Below Average';
    } else {
        return 'Poor';
    }
}

function getBonus(performanceRating) {
    switch (performanceRating) {
        case 'Excellent':
            return '20%';
        case 'Good':
            return '15%';
        case 'Average':
            return '10%';
        case 'Below Average':
            return '5%';
        default:
            return 'No bonus';
    }
}

function editEmployee(name) {
    currentEditEmployee = name;
    const employeeData = JSON.parse(localStorage.getItem('employeeData')) || [];
    const employee = employeeData.find(emp => emp.name === name);

    if (employee) {
        document.getElementById('popupCreditScore').value = employee.creditScore;
        document.getElementById('popup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }
}

function saveEdit() {
    const newCreditScore = parseInt(document.getElementById('popupCreditScore').value);
    const employeeData = JSON.parse(localStorage.getItem('employeeData')) || [];
    const employeeIndex = employeeData.findIndex(emp => emp.name === currentEditEmployee);

    if (employeeIndex !== -1) {
        const performanceRating = getPerformanceRating(newCreditScore);
        const bonus = getBonus(performanceRating);
        employeeData[employeeIndex] = {
            name: currentEditEmployee,
            creditScore: newCreditScore,
            performanceRating: performanceRating,
            bonus: bonus
        };

        localStorage.setItem('employeeData', JSON.stringify(employeeData));
        closePopup();
        displayEmployeeData();
    }
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function displayEmployeeData() {
    const employeeTable = document.getElementById('employeeTable').getElementsByTagName('tbody')[0];
    employeeTable.innerHTML = '';
    const employeeData = JSON.parse(localStorage.getItem('employeeData')) || [];

    employeeData.forEach(employee => {
        const row = employeeTable.insertRow();
        row.insertCell(0).innerText = employee.name;
        row.insertCell(1).innerText = employee.creditScore;
        row.insertCell(2).innerText = employee.performanceRating;
        row.insertCell(3).innerText = employee.bonus;

        const editCell = row.insertCell(4);
        const editButton = document.createElement('button');
        editButton.innerText = 'Edit';
        editButton.onclick = () => editEmployee(employee.name);
        editCell.appendChild(editButton);

        row.className = getClassByPerformanceRating(employee.performanceRating);
    });
}

function getClassByPerformanceRating(performanceRating) {
    switch (performanceRating) {
        case 'Excellent':
            return 'excellent';
        case 'Good':
            return 'good';
        case 'Average':
            return 'average';
        case 'Below Average':
            return 'below-average';
        case 'Poor':
            return 'poor';
    }
}

function loadEmployeeData() {
    displayEmployeeData();
}