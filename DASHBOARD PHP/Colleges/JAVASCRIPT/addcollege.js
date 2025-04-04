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
    document.getElementById('collegeForm').reset();
}

function cancelForm() {
    window.location.href = "../HTML/colleges.html";
}

function showConfirmationModal() {
    const form = document.getElementById('collegeForm');
    const collId = form.collid.value.trim();
    const fullName = form.collfullname.value.trim();
    const shortName = form.collshortname.value.trim();
    let errors = [];

    if (!collId || !fullName || !shortName) {
        errors.push("All required fields must be filled.");
    }
    if (/[^0-9]/.test(collId)) {
        errors.push("College ID should not contain any letters.");
    }
    if (/[^a-zA-Z\s]/.test(fullName)) {
        errors.push("Full College Name should not have numbers or special characters.");
    }
    if (/[^a-zA-Z\s]/.test(shortName)) {
        errors.push("Short College Name should not have numbers or special characters.");
    }

    if (errors.length > 0) {
        showErrorModal(errors.join(" "));
    } else {
        checkCollegeId(collId)
            .then(exists => {
                if (exists) {
                    showErrorModal("College ID already exists! Please use a unique ID.");
                } else {
                    const modal = document.getElementById("confirmationModal");
                    modal.style.display = "block"; 
                }
            })
            .catch(error => showErrorModal("Error checking College ID: " + error.message));
    }
}

function checkCollegeId(collId) {
    return axios.get("../PHP/addcollege.php", {
        params: { check_coll_id: true, college_id: collId }
    })
        .then(response => response.data.exists)
        .catch(error => {
            throw new Error(error);
        });
}

function confirmSubmission() {
    const form = document.getElementById('collegeForm');
    const formData = new FormData(form);

    axios.post("../PHP/addcollege.php", formData)
        .then(response => response.data)
        .then(result => {
            if (result.status === 'success') {
                window.location.href = "../HTML/colleges.html"; 
            } else {
                showErrorModal(result.errors.join(" ")); 
            }
        })
        .catch(error => showErrorModal("Error submitting form: " + error.message));
}
