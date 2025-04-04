document.addEventListener("DOMContentLoaded", function () {
    const collegeSelect = document.getElementById("deptcollid");

    fetch("../PHP/adddepartment.php?colleges=true")
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (!data || data.error) {
                showErrorModal(data.error || "Error loading colleges.");
                return;
            }
            data.forEach(college => {
                const option = document.createElement("option");
                option.value = college.collid;
                option.textContent = college.collfullname;
                collegeSelect.appendChild(option);
            });
        })
        .catch(error => showErrorModal("Error loading colleges: " + error.message));
});

function showErrorModal(message) {
    const modal = document.getElementById("errorModal");
    const errorMessage = document.getElementById("errorMessage");
    if (errorMessage) {
        errorMessage.textContent = message;
    }
    modal.style.display = "block";
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = "none";
}

function clearForm() {
    document.getElementById('departmentForm').reset();
}

function cancelForm() {
    window.location.href = "../HTML/departments.html";
}

function showConfirmationModal() {
    const form = document.getElementById('departmentForm');
    const deptId = form.deptid.value.trim();
    const fullName = form.deptfullname.value.trim();
    const collegeId = form.deptcollid.value;  
    let errors = [];

    if (!deptId || !fullName || !collegeId) {  
        errors.push("All required fields must be filled.");
    }

    if (!/^\d+$/.test(deptId)) {
        errors.push("Department ID must be a valid number.");
    }

    if (/[^a-zA-Z0-9\s]/.test(fullName)) {
        errors.push("Full Department Name contains invalid characters.");
    }

    if (/[^0-9]/.test(deptId)) {
        errors.push("Department ID should only contain numbers.");
    }

    if (/\d|[^a-zA-Z\s]/.test(fullName)) {
        errors.push("Department Name should only contain letters and spaces (no numbers or special characters).");
    }

    if (errors.length > 0) {
        showErrorModal(errors.join(" "));
    } else {
        checkDepartmentId(deptId)
            .then(exists => {
                if (exists) {
                    showErrorModal("Department ID already exists! Please use a unique ID.");
                } else {
                    const modal = document.getElementById("confirmationModal");
                    modal.style.display = "block";
                }
            })
            .catch(error => showErrorModal("Error checking Department ID: " + error.message));
    }
}

function checkDepartmentId(deptId) {
    return axios.get("../PHP/adddepartment.php", {
        params: { check_dept_id: true, department_id: deptId }
    })
        .then(response => response.data.exists)
        .catch(error => {
            throw new Error(error);
        });
}

function confirmSubmission() {
    const form = document.getElementById('departmentForm');
    const formData = new FormData(form);

    axios.post("../PHP/adddepartment.php", formData)
        .then(response => response.data)
        .then(result => {
            if (result.status === 'success') {
                fetchDepartments();
                window.location.href = "../HTML/departments.html";
            } else {
                showErrorModal(result.errors.join(" "));
            }
        })
        .catch(error => showErrorModal("Error submitting form: " + error.message));
}

function fetchDepartments() {
    fetch("../PHP/adddepartment.php?fetch_departments=true")
        .then(response => response.json())
        .then(data => {
            if (data && data.departments) {
                const departmentSelect = document.getElementById("departmentSelect"); 
                departmentSelect.innerHTML = ''; 

                data.departments.forEach(department => {
                    const option = document.createElement("option");
                    option.value = department.deptid;
                    option.textContent = department.deptname;
                    departmentSelect.appendChild(option);
                });
            }
        })
        .catch(error => showErrorModal("Error loading departments: " + error.message));
}
