document.addEventListener("DOMContentLoaded", function () {
    const errorModal = document.getElementById("errorModal");
    const errorMessage = document.getElementById("errorMessage");
    const departmentsTableBody = document.getElementById("departmentsTableBody");

    fetch("../PHP/departments.php")
        .then(response => response.json())
        .then(data => {

            if (data.error) {
                showErrorModal(data.error);
                return;
            }

            document.getElementById('username').textContent = data.username;

            if (data.departments && data.departments.length > 0) {
                let rows = "";
                data.departments.forEach(department => {
                    rows += `
                        <tr>
                            <td>${department.deptid}</td>
                            <td>${department.deptname}</td>
                            <td>${department.deptshortname}</td>
                            <td>${department.collegename}</td>
                            <td>
                                <a href="../HTML/editdepartment.html?id=${department.deptid}">
                                    <i class="bi-pencil-square"></i>
                                </a>
                                <a href="../HTML/deletedepartment.html?id=${department.deptid}">
                                    <i class="bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
                departmentsTableBody.innerHTML = rows;
            } else {
                departmentsTableBody.innerHTML = `
                    <tr>
                        <td colspan="5">No departments found</td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error("Error fetching data:", error);
            showErrorModal("Error loading departments. Please try again later.");
        });

    function showErrorModal(message) {
        if (errorMessage && errorModal) {
            errorMessage.textContent = message;
            errorModal.style.display = "block";
        } else {
            console.error("Error modal or message element not found.");
        }
    }

    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "none";
        } else {
            console.error(`Modal with ID '${modalId}' not found.`);
        }
    };
});
