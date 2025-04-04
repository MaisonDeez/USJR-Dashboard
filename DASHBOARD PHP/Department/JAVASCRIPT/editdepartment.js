document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const deptId = urlParams.get("id");

    if (!deptId) {
        showErrorModal("No department ID provided.");
        return;
    }

    axios.get(`../PHP/editdepartment.php?id=${deptId}`)
        .then(response => {
            if (response.data.error) {
                showErrorModal(response.data.error);
                return;
            }

            const { department, colleges } = response.data;

            document.getElementById("dept_id").value = department.deptid;
            document.getElementById("deptfullname").value = department.deptfullname;
            document.getElementById("deptshortname").value = department.deptshortname;

            const collegeSelect = document.getElementById("deptcollid");
            colleges.forEach(college => {
                const option = document.createElement("option");
                option.value = college.collid;
                option.textContent = college.collfullname;
                if (college.collid === department.deptcollid) {
                    option.selected = true;
                }
                collegeSelect.appendChild(option);
            });
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

    const form = document.getElementById("editDepartmentForm");
    const deptId = form.dept_id.value.trim();
    const deptFullName = form.deptfullname.value.trim();
    const deptShortName = form.deptshortname.value.trim();
    const collegeId = form.deptcollid.value.trim();

    const errors = [];

    if (!deptId || !deptFullName || !deptShortName || !collegeId) {
        errors.push("All fields are required.");
    }
    if (/[^a-zA-Z\s]/.test(deptFullName) || /[^a-zA-Z\s]/.test(deptShortName)) {
        errors.push("Department names should not contain numbers or special characters.");
    }

    if (errors.length > 0) {
        showErrorModal(errors.join(" "));
        return;
    }

    const formData = new FormData(form);

    axios.post("../PHP/editdepartment.php", formData)
        .then(response => {
            if (response.data.error) {
                showErrorModal(response.data.error);
            } else {
                window.location.href = "../HTML/departments.html";
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
    window.location.href = "../HTML/departments.html";
}
