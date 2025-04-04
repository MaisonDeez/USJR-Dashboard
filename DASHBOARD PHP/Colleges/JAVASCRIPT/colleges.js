document.addEventListener("DOMContentLoaded", function () {
    const errorModal = document.getElementById("errorModal");
    const errorMessage = document.getElementById("errorMessage");
    const collegesTableBody = document.querySelector("#colleges-table tbody");

    fetch("../PHP/colleges.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showErrorModal(data.error);
                return;
            }

            document.getElementById('username').textContent = data.username;

            if (data.colleges && data.colleges.length > 0) {
                let rows = "";
                data.colleges.forEach(college => {
                    rows += `
                        <tr>
                            <td>${college.collid}</td>
                            <td>${college.collfullname}</td>
                            <td>${college.collshortname}</td>
                            <td>
                                <a href="../HTML/editcollege.html?id=${college.collid}">
                                    <i class="bi-pencil-square"></i>
                                </a>
                                <a href="../HTML/deletecollege.html?id=${college.collid}">
                                    <i class="bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
                collegesTableBody.innerHTML = rows;
            } else {
                collegesTableBody.innerHTML = `
                    <tr>
                        <td colspan="4">No records found</td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error("Error fetching colleges:", error);
            showErrorModal("Error loading colleges. Please try again later.");
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
