document.addEventListener("DOMContentLoaded", function () {
    loadColleges();
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
    document.getElementById('programForm').reset();
    document.getElementById('progcolldeptid').innerHTML = '<option value="">Select Department</option>';
}

function cancelForm() {
    window.location.href = "../HTML/programs.html";
}

function loadColleges() {
    axios.get('../PHP/addprogram.php?colleges=true')
        .then(response => {
            const collegesDropdown = document.getElementById('progcollid');
            collegesDropdown.innerHTML = '<option value="">Select College</option>';

            response.data.forEach(college => {
                const option = document.createElement('option');
                option.value = college.collid;
                option.textContent = college.collfullname;
                collegesDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching colleges:', error));
}

function loadDepartments() {
    const collegeId = document.getElementById('progcollid').value;
    if (collegeId) {
        axios.get(`../PHP/addprogram.php?fetch_departments=true&college_id=${collegeId}`)
            .then(response => {
                const departmentsDropdown = document.getElementById('progcolldeptid');
                departmentsDropdown.innerHTML = '<option value="">Select Department</option>';

                response.data.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.deptid;
                    option.textContent = department.deptname;
                    departmentsDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching departments:', error));
    }
}

document.getElementById('progcollid').addEventListener('change', loadDepartments);

function showConfirmationModal() {
    const form = document.getElementById('programForm');
    const progId = form.progid.value.trim();
    const fullName = form.progfullname.value.trim();
    const shortName = form.progshortname.value.trim();
    const collegeId = form.progcollid.value;
    const departmentId = form.progcolldeptid.value;

    let errors = [];

    if (!progId || !fullName || !collegeId || !departmentId) {
        errors.push("All fields are required.");
    }
    if (/[^a-zA-Z\s-]/.test(fullName)) {
        errors.push("Full Program Name should not contain numbers or special characters");
    }
    if (/[^a-zA-Z\s-]/.test(shortName) && shortName !== '') {
        errors.push("Short Program Name should not contain numbers or special characters.");
    }
    if (/[^0-9]/.test(progId)) {
        errors.push("Program ID should not contain any letters or special characters.");
    }

    if (errors.length > 0) {
        showErrorModal(errors.join(" "));
        return;
    }

    checkProgramId(progId)
        .then(exists => {
            if (exists) {
                showErrorModal("Program ID already exists! Please input another.");
            } else {
                document.getElementById('confirmationModal').style.display = 'block';
            }
        })
        .catch(error => showErrorModal('Error checking Program ID: ' + error.message));
}

function checkProgramId(progId) {
    return axios.get(`../PHP/addprogram.php?check_program_id=${progId}`)
        .then(response => response.data.exists)
        .catch(error => {
            console.error("Error checking Program ID:", error);
            return false;
        });
}

function confirmProgramSubmission() {
    const form = document.getElementById('programForm');
    const formData = new FormData(form);

    axios.post('../PHP/addprogram.php', formData)
        .then(response => {
            if (response.data.status === 'success') {
                window.location.href = "../HTML/programs.html";
            } else {
                showErrorModal("Error submitting form. Please try again.");
            }
        })
        .catch(error => showErrorModal("Error submitting form: " + error.message));
}
