document.addEventListener("DOMContentLoaded", function () {
    const errorModal = document.getElementById("errorModal");
    const errorMessage = document.getElementById("errorMessage");
    const programTableBody = document.querySelector("#program-table tbody");

    fetch("../PHP/programs.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showErrorModal(data.error);
                return;
            }

            document.getElementById('username').textContent = data.username;

            if (data.programs && data.programs.length > 0) {
                let rows = "";
                data.programs.forEach(program => {
                    rows += `
                        <tr>
                            <td>${program.progid}</td>
                            <td>${program.progfullname}</td>
                            <td>${program.progshortname}</td>
                            <td>${program.progcollid}</td>
                            <td>${program.progcolldeptid}</td>
                            <td>
                                <a href="../HTML/editprogram.html?id=${program.progid}">
                                    <i class="bi-pencil-square"></i>
                                </a>
                                <a href="../HTML/deleteprogram.html?id=${program.progid}">
                                    <i class="bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
                programTableBody.innerHTML = rows;
            } else {
                programTableBody.innerHTML = `
                    <tr>
                        <td colspan="6">No records found</td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error("Error fetching data:", error);
            showErrorModal("Error loading programs. Please try again later.");
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
