<?php
session_start();
require 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['adminID'])) {
    header("Location: login.php");
    exit;
}

// Handle Add/Edit/Delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO Subject (subjectName, teacherID, period, time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $_POST['subjectName'], $_POST['teacherID'], $_POST['period'], $_POST['time']);
        $stmt->execute();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $stmt = $conn->prepare("UPDATE Subject SET subjectName = ?, teacherID = ?, period = ?, time = ? WHERE subjectID = ?");
        $stmt->bind_param("sissi", $_POST['subjectName'], $_POST['teacherID'], $_POST['period'], $_POST['time'], $_POST['subjectID']);
        $stmt->execute();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $stmt = $conn->prepare("DELETE FROM Subject WHERE subjectID = ?");
        $stmt->bind_param("i", $_POST['subjectID']);
        $stmt->execute();
    }
}

// Fetch subjects and teachers
$subjects = $conn->query("SELECT Subject.*, Teacher.firstName, Teacher.lastName FROM Subject LEFT JOIN Teacher ON Subject.teacherID = Teacher.teacherID");
$teachers = $conn->query("SELECT * FROM Teacher");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Responsive Design */
        .manage-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .add-button, .edit-button, .delete-button {
            padding: 8px 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        .edit-button {
            background-color: #28a745;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .form-container {
            display: none;
            margin-top: 20px;
        }

        .form-container input, .form-container select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .form-container button {
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .cancel-button {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="manage-container">
        <h2>Manage Subjects</h2>
        <button id="addSubject" class="add-button">Add New Subject</button>
        
        <!-- Add/Edit Subject Form -->
        <div id="subjectForm" class="form-container">
            <h3 id="formTitle">Add New Subject</h3>
            <form method="POST">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="subjectID" id="subjectID">
                
                <label for="subjectName">Subject Name:</label>
                <input type="text" name="subjectName" id="subjectName" required>
                
                <label for="teacherID">Assign Teacher:</label>
                <select name="teacherID" id="teacherID" required>
                    <option value="">Select a Teacher</option>
                    <?php while ($teacher = $teachers->fetch_assoc()) : ?>
                        <option value="<?php echo $teacher['teacherID']; ?>"><?php echo $teacher['firstName'] . ' ' . $teacher['lastName']; ?></option>
                    <?php endwhile; ?>
                </select>
                
                <label for="period">Period:</label>
                <input type="text" name="period" id="period" required>
                
                <label for="time">Time:</label>
                <input type="time" name="time" id="time" required>
                
                <button type="submit" class="add-button">Save</button>
                <button type="button" id="cancelForm" class="cancel-button">Cancel</button>
            </form>
        </div>
        
        <!-- Subjects Table -->
        <table>
            <thead>
                <tr>
                    <th>Subject ID</th>
                    <th>Subject Name</th>
                    <th>Teacher</th>
                    <th>Period</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($subject = $subjects->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $subject['subjectID']; ?></td>
                    <td><?php echo $subject['subjectName']; ?></td>
                    <td><?php echo $subject['firstName'] . ' ' . $subject['lastName']; ?></td>
                    <td><?php echo $subject['period']; ?></td>
                    <td><?php echo $subject['time']; ?></td>
                    <td>
                        <button class="edit-button editSubject" data-id="<?php echo $subject['subjectID']; ?>" data-name="<?php echo $subject['subjectName']; ?>" data-teacher="<?php echo $subject['teacherID']; ?>" data-period="<?php echo $subject['period']; ?>" data-time="<?php echo $subject['time']; ?>">Edit</button>
                        <button class="delete-button deleteSubject" data-id="<?php echo $subject['subjectID']; ?>">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Show the add subject form when the Add New Subject button is clicked
            $('#addSubject').click(function() {
                $('#formTitle').text('Add New Subject');
                $('#formAction').val('add');
                $('#subjectID').val('');
                $('#subjectName').val('');
                $('#teacherID').val('');
                $('#period').val('');
                $('#time').val('');
                $('#subjectForm').show();
            });

            // Hide the form when the Cancel button is clicked
            $('#cancelForm').click(function() {
                $('#subjectForm').hide();
            });

            // Show the edit form with pre-filled data
            $('.editSubject').click(function() {
                $('#formTitle').text('Edit Subject');
                $('#formAction').val('edit');
                $('#subjectID').val($(this).data('id'));
                $('#subjectName').val($(this).data('name'));
                $('#teacherID').val($(this).data('teacher'));
                $('#period').val($(this).data('period'));
                $('#time').val($(this).data('time'));
                $('#subjectForm').show();
            });

            // Handle delete subject
            $('.deleteSubject').click(function() {
                var subjectID = $(this).data('id');
                if (confirm("Are you sure you want to delete this subject?")) {
                    $.post('manage_subjects.php', { action: 'delete', subjectID: subjectID }, function(response) {
                        location.reload();
                    });
                }
            });
        });
    </script>
</body>
</html>
