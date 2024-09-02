document.getElementById('saveClass').addEventListener('click', function() {
    const className = document.getElementById('className').value;
    const classTeacher = document.getElementById('classTeacher').value;
    const students = document.getElementById('students').value.split(',');

    if (className && classTeacher && students.length > 0) {
        const classList = document.getElementById('classList');

        const classItem = document.createElement('div');
        classItem.className = 'class-item';

        const classTitle = document.createElement('h4');
        classTitle.innerText = `Class: ${className}`;
        classItem.appendChild(classTitle);

        const teacherInfo = document.createElement('p');
        teacherInfo.innerText = `Class Teacher: ${classTeacher}`;
        classItem.appendChild(teacherInfo);

        const studentInfo = document.createElement('p');
        studentInfo.innerText = `Students: ${students.join(', ')}`;
        classItem.appendChild(studentInfo);

        classList.appendChild(classItem);

        // Clear input fields
        document.getElementById('className').value = '';
        document.getElementById('classTeacher').value = '';
        document.getElementById('students').value = '';
    } else {
        alert('Please fill out all fields.');
    }
});
