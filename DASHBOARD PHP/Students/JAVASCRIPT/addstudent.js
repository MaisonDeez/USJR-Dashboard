document.addEventListener("DOMContentLoaded", function () {
    const collegeSelect = document.getElementById("college");
    const programSelect = document.getElementById("program");

    fetch("../PHP/addstudent.php?colleges=true")
        .then(response => response.json())
        .then(data => {
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

    collegeSelect.addEventListener("change", function () {
        const collegeId = this.value;
        if (collegeId) {
            fetchPrograms(collegeId);
        } else {
            programSelect.innerHTML = '<option value="">Select Program</option>';
        }
    });
 
    function fetchPrograms(collegeId) {
        axios.get("../PHP/addstudent.php", {
            params: { college: collegeId }
        })
            .then(response => {
                const programs = response.data; 
                programSelect.innerHTML = '<option value="">Select Program</option>'; 
                if (Array.isArray(programs)) { 
                    programs.forEach(program => {
                        const option = document.createElement("option");
                        option.value = program.progid;
                        option.textContent = program.progfullname;
                        programSelect.appendChild(option);
                    });
                }
            })
            .catch(error => showErrorModal('Error fetching programs: ' + error.message));
    }
});

function showErrorModal(message) {
    const modal = document.getElementById("errorModal");
    const errorMessage = document.getElementById("errorMessage");
    modal.style.display = "block";
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = "none";
}

function clearForm() {
    document.getElementById('studentForm').reset();
}

function cancelForm() {
    window.location.href = "../HTML/students.html";
}

function showConfirmationModal() {
    const form = document.getElementById('studentForm');
    const studentId = form.student_id.value.trim();
    const firstName = form.first_name.value.trim();
    const middleName = form.middle_name.value.trim();
    const lastName = form.last_name.value.trim();
    const year = form.year.value.trim();

    let errors = [];

    if (!studentId || !firstName || !lastName || !year || form.college.value === '' || form.program.value === '') {
        errors.push("All fields are required.");
    }
    if (/[^a-zA-Z]/.test(firstName) || /[^a-zA-Z]/.test(middleName) || /[^a-zA-Z]/.test(lastName)) {
        errors.push("Names should not contain numbers or special characters.");
    }
    if (!/^\d+$/.test(year) || year < 1 || year > 5) {
        errors.push("Invalid year level. Year must be between 1 and 5.");
    }
    if (/[^0-9]/.test(studentId)) {
        errors.push("Student ID should only contain numbers.");
    }

    if (errors.length > 0) {
        showErrorModal(errors.join(" "));
    } else {
        checkStudentId(studentId)
            .then(exists => {
                if (exists) {
                    showErrorModal("Student ID already exists! Please input another.");
                } else {
                    document.getElementById("confirmationModal").style.display = "block";
                }
            })
            .catch(error => {
                showErrorModal('Error checking student ID: ' + error.message);
            });
    }
}

function checkStudentId(studentId) {
    return axios.get("../PHP/addstudent.php", {
        params: { checkStudentId: studentId }
    })
        .then(response => {
            return response.data.exists;
        })
        .catch(error => {
            console.error('Error checking student ID:', error.message);
            throw error;
        });
}

function confirmSubmission() {
    const form = document.getElementById('studentForm');
    const formData = new FormData(form); 
    axios.post("../PHP/addstudent.php", formData)
        .then(response => {
            if (response.data.status === 'success') {
                window.location.href = "../HTML/students.html";
            } else {
                showErrorModal(response.data.errors.join(" "));
            }
        })
        .catch(error => showErrorModal("Error submitting form: " + error.message));
}
