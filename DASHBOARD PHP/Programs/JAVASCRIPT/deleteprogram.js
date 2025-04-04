document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const programId = urlParams.get('id');

    if (programId) {
        axios.get('../PHP/deleteprogram.php', {
            params: { id: programId }
        })
        .then(function (response) {
            console.log('Response data:', response.data);
            const program = response.data;
            if (program) {
                document.getElementById('progid').value = program.progid;
                document.getElementById('progfullname').value = program.progfullname;
                document.getElementById('progshortname').value = program.progshortname;
                document.getElementById('departmentName').value = program.departmentName;
                document.getElementById('collegeName').value = program.collegeName;
            } else {
                alert('Program not found.');
                window.location.href = 'programs.php';
            }
        })
        .catch(function (error) {
            console.error('Error fetching program details:', error);
            alert('There was an error fetching program details.');
        });
    }

    window.showModal = function () {
        document.getElementById('warningModal').style.display = 'block';
    };

    window.closeModal = function () {
        document.getElementById('warningModal').style.display = 'none';
    };

    window.onclick = function (event) {
        if (event.target === document.getElementById('warningModal')) {
            closeModal();
        }
    };

    window.deleteProgram = function () {
        const progId = document.getElementById('progid').value;

        axios.post('../PHP/deleteprogram.php', {
            progid: progId,
            proceed_delete: true
        })
        .then(function () {
            window.location.href = 'programs.html';
        })
        .catch(function (error) {
            console.error('Error deleting program:', error);
            alert('There was an error deleting the program.');
        });
    };
});
