document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const studentId = urlParams.get('id');

    if (studentId) {
        axios.get('../PHP/deletestudent.php', {
            params: { id: studentId }
        })
        .then(function(response) {
            const student = response.data;
            if (student) {
                document.getElementById('student_id').value = student.studid;
                document.getElementById('last_name').value = student.studlastname;
                document.getElementById('first_name').value = student.studfirstname;
                document.getElementById('middle_name').value = student.studmidname;
                document.getElementById('college').value = student.collfullname;
                document.getElementById('program').value = student.progfullname;
                document.getElementById('year').value = student.studyear;
            } else {
                alert('Student not found.');
                window.location.href = '../HTML/students.html';
            }
        })
        .catch(function(error) {
            console.error('Error fetching student details:', error);
            alert('There was an error fetching student details.');
        });
    }

    window.showModal = function() {
        document.getElementById('warningModal').style.display = 'block';
    }

    window.closeModal = function() {
        document.getElementById('warningModal').style.display = 'none';
    }

    window.deleteStudent = function() {
        const student_id = document.getElementById('student_id').value;

        axios.post('../PHP/deletestudent.php', {
            student_id: student_id,
            proceed_delete: true
        })
        .then(function(response) {
            if (response.data.status === 'success') {
                window.location.href = '../HTML/students.html';
            } else {
                alert('There was an error deleting the student: ' + response.data.message);
            }
        })
        .catch(function(error) {
            console.error('Error deleting student:', error);
            alert('There was an error deleting the student.');
        });
    }
});
