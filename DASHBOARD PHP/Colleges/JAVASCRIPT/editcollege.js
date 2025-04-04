document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const collegeId = urlParams.get("id");

    if (!collegeId) {
        showErrorModal("No college ID provided.");
        return;
    }

    axios.get(`../PHP/editcollege.php?id=${collegeId}`)
        .then(response => {
            if (response.data.error) {
                showErrorModal(response.data.error);
                return;
            }

            const { college } = response.data;

            document.getElementById("college_id").value = college.collid;
            document.getElementById("college_name").value = college.collfullname;
            document.getElementById("college_shortname").value = college.collshortname;
        })
        .catch(error => showErrorModal("Error fetching data: " + error.message));
});

function showConfirmationModal() {
    document.getElementById("confirmationModal").style.display = "flex";
}

function closeConfirmationModal() {
    document.getElementById("confirmationModal").style.display = "none";
}

function confirmEdit() {
    closeConfirmationModal();

    const form = document.getElementById("editCollegeForm");
    const collegeId = form.college_id.value.trim();
    const collegeName = form.college_name.value.trim();
    const collegeShortName = form.college_shortname.value.trim();

    const errors = [];

    if (!collegeId) {
        errors.push("College ID is required.");
    }
    if (!collegeName) {
        errors.push("College Name is required.");
    }
    if (!collegeShortName) {
        errors.push("College Short Name is required.");
    }

    if (collegeName && /[^a-zA-Z ]/.test(collegeName)) {
        errors.push("College Name should not contain numbers or special characters.");
    }

    if (collegeShortName && /[^a-zA-Z ]/.test(collegeShortName)) {
        errors.push("College Short Name should not contain numbers or special characters.");
    }

    if (errors.length > 0) {
        showErrorModal(errors.join(" "));
        return;
    }

    const formData = new FormData(form);

    axios.post("../PHP/editcollege.php", formData)
        .then(response => {
            if (response.data.error) {
                showErrorModal(response.data.error);
            } else {
                window.location.href = "../HTML/colleges.html";
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
    window.location.href = "../HTML/colleges.html";
}
