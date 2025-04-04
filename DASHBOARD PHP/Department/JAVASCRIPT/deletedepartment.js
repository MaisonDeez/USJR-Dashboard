document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const departmentId = urlParams.get('id');

    if (departmentId) {
        axios.get('../PHP/deletedepartment.php', {
            params: { id: departmentId }
        })
        .then(function (response) {
            const department = response.data;
            if (department) {
                document.getElementById('deptid').value = department.deptid;
                document.getElementById('deptfullname').value = department.deptfullname;
                document.getElementById('deptshortname').value = department.deptshortname;
                document.getElementById('collegeName').value = department.collegeName;
            } else {
                alert('Department not found.');
                window.location.href = 'departments.php';
            }
        })
        .catch(function (error) {
            console.error('Error fetching department details:', error);
            alert('There was an error fetching department details.');
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

    window.deleteDepartment = function () {
        const deptId = document.getElementById('deptid').value;

        axios.post('../PHP/deletedepartment.php', {
            deptid: deptId,
            proceed_delete: true
        })
        .then(function () {
            window.location.href = 'departments.html';
        })
        .catch(function (error) {
            console.error('Error deleting department:', error);
            alert('There was an error deleting the department.');
        });
    };
});
