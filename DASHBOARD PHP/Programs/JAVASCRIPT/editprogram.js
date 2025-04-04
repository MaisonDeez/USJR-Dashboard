document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const programId = urlParams.get("id");

    if (!programId) {
        showErrorModal("No program ID provided.");
        return;
    }

    axios.get(`../PHP/editprogram.php?id=${programId}`)
        .then(response => {
            if (response.data.error) {
                showErrorModal(response.data.error);
                return;
            }

            const { program, colleges, departments } = response.data;

            document.getElementById("progid").value = program.progid;
            document.getElementById("progfullname").value = program.progfullname;
            document.getElementById("progshortname").value = program.progshortname;

            populateSelect("progcollid", colleges, program.progcollid);
            populateSelect("progcolldeptid", departments, program.progcolldeptid);
        })
        .catch(error => showErrorModal("Error fetching data: " + error.message));
});

function populateSelect(selectId, items, selectedValue) {
    const select = document.getElementById(selectId);
    select.innerHTML = "";

    items.forEach(item => {
        const option = document.createElement("option");

        if (selectId === "progcollid") {
            option.value = item.collid;
            option.textContent = item.collfullname;
        } else if (selectId === "progcolldeptid") {
            option.value = item.deptid;
            option.textContent = item.deptfullname;
        }

        if (option.value == selectedValue) {
            option.selected = true;
        }
        select.appendChild(option);
    });
}

function showConfirmationModal() {
    document.getElementById("confirmationModal").style.display = "flex";
}

function closeConfirmationModal() {
    document.getElementById("confirmationModal").style.display = "none";
}

function confirmEdit() {
    closeConfirmationModal();

    const form = document.getElementById("editProgramForm");
    const progid = form.progid.value.trim();
    const progfullname = form.progfullname.value.trim();
    const progshortname = form.progshortname.value.trim();
    const progcollid = form.progcollid.value.trim();
    const progcolldeptid = form.progcolldeptid.value.trim();

    const errors = [];

    if (!progid || !progfullname || !progshortname || !progcollid || !progcolldeptid) {
        errors.push("All fields are required.");
    }

    if (/[^a-zA-Z\s]/.test(progfullname)) {
        errors.push("Program Full Name should not contain numbers or special characters.");
    }

    if (/[^a-zA-Z\s]/.test(progshortname)) {
        errors.push("Program Short Name should not contain numbers or special characters.");
    }

    if (errors.length > 0) {
        showErrorModal(errors.join(" "));
        return;
    }

    const formData = new FormData(form);

    axios.post("../PHP/editprogram.php", formData)
        .then(response => {
            if (response.data.error) {
                showErrorModal(response.data.error);
            } else {
                window.location.href = "../HTML/programs.html";
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
    window.location.href = "../HTML/programs.html";
}
