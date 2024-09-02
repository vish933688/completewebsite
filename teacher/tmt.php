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
        $stmt = $conn->prepare("INSERT INTO Teacher (firstName, lastName, email, phoneNumber, subject) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['phoneNumber'], $_POST['subject']);
        $stmt->execute();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $stmt = $conn->prepare("UPDATE Teacher SET firstName = ?, lastName = ?, email = ?, phoneNumber = ?, subject = ? WHERE teacherID = ?");
        $stmt->bind_param("sssssi", $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['phoneNumber'], $_POST['subject'], $_POST['teacherID']);
        $stmt->execute();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $stmt = $conn->prepare("DELETE FROM Teacher WHERE teacherID = ?");
        $stmt->bind_param("i", $_POST['teacherID']);
        $stmt->execute();
    }
}

// Fetch teachers
$teachers = $conn->query("SELECT * FROM Teacher");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Teachers</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Responsive Design */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .manage-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
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

        .form-container input {
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

        @media (max-width: 768px) {
            .manage-container {
                padding: 10px;
            }

            table, th, td {
                font-size: 14px;
            }

            .add-button, .edit-button, .delete-button {
                padding: 6px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="manage-container">
        <h2>Manage Teachers</h2>
        <button id="addTeacher" class="add-button">Add New Teacher</button>
        
        <!-- Add/Edit Teacher Form -->
        <div id="teacherForm" class="form-container">
            <h3 id="formTitle">Add New Teacher</h3>
            <form method="POST">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="teacherID" id="teacherID">
                
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" id="firstName" required>
                
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" id="lastName" required>
                
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                
                <label for="phoneNumber">Phone Number:</label>
                <input type="text" name="phoneNumber" id="phoneNumber" required>
                
                <label for="subject">Subject:</label>
                <input type="text" name="subject" id="subject" required>
                
                <button type="submit" class="add-button">Save</button>
                <button type="button" id="cancelForm" class="cancel-button">Cancel</button>
            </form>
        </div>
        
        <!-- Teachers Table -->
        <table>
            <thead>
                <tr>
                    <th>Teacher ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Subject</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($teacher = $teachers->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $teacher['teacherID']; ?></td>
                    <td><?php echo $teacher['firstName']; ?></td>
                    <td><?php echo $teacher['lastName']; ?></td>
                    <td><?php echo $teacher['email']; ?></td>
                    <td><?php echo $teacher['phoneNumber']; ?></td>
                    <td><?php echo $teacher['subjectSpecialization']; ?></td>
                    <td>
                        <button class="edit-button editTeacher" data-id="<?php echo $teacher['teacherID']; ?>" data-firstname="<?php echo $teacher['firstName']; ?>" data-lastname="<?php echo $teacher['lastName']; ?>" data-email="<?php echo $teacher['email']; ?>" data-phone="<?php echo $teacher['phoneNumber']; ?>" data-subject="<?php echo $teacher['subjectSpecialization']; ?>">Edit</button>
                        <button class="delete-button deleteTeacher" data-id="<?php echo $teacher['teacherID']; ?>">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Show the add teacher form when the Add New Teacher button is clicked
            $('#addTeacher').click(function() {
                $('#formTitle').text('Add New Teacher');
                $('#formAction').val('add');
                $('#teacherID').val('');
                $('#firstName').val('');
                $('#lastName').val('');
                $('#email').val('');
                $('#phoneNumber').val('');
                $('#subject').val('');
                $('#teacherForm').show();
            });

            // Hide the form when the Cancel button is clicked
            $('#cancelForm').click(function() {
                $('#teacherForm').hide();
            });

            // Show the edit form with pre-filled data
            $('.editTeacher').click(function() {
                $('#formTitle').text('Edit Teacher');
                $('#formAction').val('edit');
                $('#teacherID').val($(this).data('id'));
                $('#firstName').val($(this).data('firstname'));
                $('#lastName').val($(this).data('lastname'));
                $('#email').val($(this).data('email'));
                $('#phoneNumber').val($(this).data('phone'));
                $('#subject').val($(this).data('subject'));
                $('#teacherForm').show();
            });

            // Handle delete teacher
            $('.deleteTeacher').click(function() {
                var teacherID = $(this).data('id');
                if (confirm("Are you sure you want to delete this teacher?")) {
                    $.post('manage_teachers.php', { action: 'delete', teacherID: teacherID }, function(response) {
                        location.reload();
                    });
                }
            });
        });
    </script>
</body>
</html>
