document.addEventListener("DOMContentLoaded", function () {
    const errorModal = document.getElementById("errorModal");
    const errorMessage = document.getElementById("errorMessage");
    const studentsTableBody = document.getElementById("studentsTableBody");

    fetch("../PHP/students.php") 
        .then(response => response.json())
        .then(data => {
            if (data.error) { 
                showErrorModal(data.error);
                return;
            }

            document.getElementById('username').textContent = data.username; 

            if (data.students && data.students.length > 0) {
                let rows = "";
                data.students.forEach(student => { 
                    rows += `
                        <tr>
                            <td>${student.studid}</td>
                            <td>${student.studlastname}</td>
                            <td>${student.studfirstname}</td>
                            <td>${student.studmiddleinitial || "-"}</td>
                            <td>${student.collshortname}</td>
                            <td>${student.progshortname}</td>
                            <td>${student.studyear}</td>
                            <td>
                                <a href="../HTML/editstudent.html?id=${student.studid}">
                                    <i class="bi-pencil-square"></i>
                                </a>
                                <a href="../HTML/deletestudent.html?id=${student.studid}">
                                    <i class="bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
                studentsTableBody.innerHTML = rows;
            } else {
                studentsTableBody.innerHTML = `
                    <tr>
                        <td colspan="8">No records found</td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            showErrorModal(`Error loading students: ${error.message}`);
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
