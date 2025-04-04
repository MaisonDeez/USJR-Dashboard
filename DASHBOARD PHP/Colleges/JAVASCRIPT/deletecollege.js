document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const collId = urlParams.get('id');

    if (collId) {
        axios.get('../PHP/deletecollege.php', {
            params: { id: collId }
        })
        .then(function(response) {
            const college = response.data;
            if (college) {
                document.getElementById('collid').value = college.collid;
                document.getElementById('collfullname').value = college.collfullname;
                document.getElementById('collshortname').value = college.collshortname;
            } else {
                alert('College not found.');
                window.location.href = '../HTML/colleges.html';
            }
        })
        .catch(function(error) {
            console.error('Error fetching college details:', error);
            alert('There was an error fetching college details.');
        });
    }

    window.showModal = function() {
        document.getElementById('warningModal').style.display = 'block';
    }

    window.closeModal = function() {
        document.getElementById('warningModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('warningModal')) {
            closeModal();
        }
    }

    window.deleteCollege = function() {
        const collid = document.getElementById('collid').value;

        axios.post('../PHP/deletecollege.php', {
            collid: collid,
            proceed_delete: true
        })
        .then(function(response) {
            if (response.data.status === 'success') {
                alert('College deleted successfully.');
                window.location.href = '../HTML/colleges.html';
            } else {
                alert('There was an error deleting the college: ' + response.data.message);
            }
        })
        .catch(function(error) {
            console.error('Error deleting college:', error);
            alert('There was an error deleting the college.');
        });
    }
});
