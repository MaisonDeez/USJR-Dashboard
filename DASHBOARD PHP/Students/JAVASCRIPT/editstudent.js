document.addEventListener("DOMContentLoaded", () => { 
    const urlParams = new URLSearchParams(window.location.search); 
    const studentId = urlParams.get("id");

    if (!studentId) {
        showErrorModal("No student ID provided.");
        return;
    }

    axios.get(`../PHP/editstudent.php?id=${studentId}`)
        .then(response => {
            if (response.data.error) {
                showErrorModal(response.data.error); 
                return;
            }

            const { student, colleges, programs } = response.data; 

            document.getElementById("student_id").value = student.studid;
            document.getElementById("first_name").value = student.studfirstname;
            document.getElementById("middle_name").value = student.studmidname || "";
            document.getElementById("last_name").value = student.studlastname;
            document.getElementById("year").value = student.studyear;

            const collegeSelect = document.getElementById("college");
            colleges.forEach(college => { 
                const option = document.createElement("option"); 
                option.value = college.collid; 
                option.textContent = college.collfullname; 
                if (college.collid === student.collid) {
                    option.selected = true;
                }
                collegeSelect.appendChild(option); 
            });

            updatePrograms(student.collid, programs, student.progid); 

            collegeSelect.addEventListener("change", () => {
                updatePrograms(collegeSelect.value, programs);
            });
        })
        .catch(error => showErrorModal("Error fetching data: " + error.message));
});

function updatePrograms(selectedCollegeId, programs, selectedProgramId = null) { 
    const programSelect = document.getElementById("program");
    programSelect.innerHTML = "";

    const filteredPrograms = programs.filter(program => program.progcollid == selectedCollegeId); 

    if (filteredPrograms.length > 0) {
        filteredPrograms.forEach(program => { 
            const option = document.createElement("option");
            option.value = program.progid;
            option.textContent = program.progfullname;
            if (program.progid === selectedProgramId) {
                option.selected = true;
            }
            programSelect.appendChild(option);
        });
    } else {
        const noProgramsOption = document.createElement("option");
        noProgramsOption.value = "";
        noProgramsOption.textContent = "-----No programs available-----";
        noProgramsOption.disabled = true; 
        noProgramsOption.selected = true; 
        programSelect.appendChild(noProgramsOption);
    }
}

function showConfirmationModal() {
    document.getElementById("confirmationModal").style.display = "flex";
}

function closeConfirmationModal() {
    document.getElementById("confirmationModal").style.display = "none";
}

function confirmEdit() {
    closeConfirmationModal(); 

    const form = document.getElementById("editStudentForm");
    const studentId = form.student_id.value.trim();
    const firstName = form.first_name.value.trim();
    const middleName = form.middle_name.value.trim();
    const lastName = form.last_name.value.trim();
    const year = form.year.value.trim();
    const college = form.college.value.trim();
    const program = form.program.value.trim();

    const errors = []; 

    if (!studentId || !firstName || !lastName || !year || college === '' || program === '') {
        errors.push("All fields are required.");
    }
    if (/[^a-zA-Z]/.test(firstName) || /[^a-zA-Z]/.test(middleName) || /[^a-zA-Z]/.test(lastName)) {
        errors.push("Names should not contain numbers or special characters.");
    }
    if (!/^\d+$/.test(year) || year < 1 || year > 5) {
        errors.push("Year must be between 1 and 5.");
    }
    if (/[^0-9]/.test(studentId)) {
        errors.push("Student ID should only contain numbers.");
    }

    if (errors.length > 0) {
        showErrorModal(errors.join(" ")); 
        return;
    }    

    const formData = new FormData(form); 

    axios.post("../PHP/editstudent.php", formData) 
        .then(response => {
            if (response.data.error) { 
                showErrorModal(response.data.error);
            } else {
                window.location.href = "../HTML/students.html";
            }
        })
        .catch(error => showErrorModal("Error saving changes: " + error.message));
}

function showErrorModal(message) {
    document.getElementById("errorMessage").textContent = message;
    document.getElementById("errorModal").style.display = "flex";
}

function closeErrorModal() {
    document.getElementById("errorModal").style.display = "none";
}

function cancelEdit() {
    window.location.href = "../HTML/students.html";
}
