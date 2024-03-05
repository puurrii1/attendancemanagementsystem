<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $id = htmlspecialchars($_POST["delete"]);

    // Placeholder for database interaction
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "students";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $message = "";

    // Check if the student attendance record exists
    $checkAttendanceSql = $conn->prepare("SELECT * FROM attendance WHERE student_id = ?");
    $checkAttendanceSql->bind_param("i", $id);
    $checkAttendanceSql->execute();
    $checkResult = $checkAttendanceSql->get_result();

    if ($checkResult->num_rows > 0) {
        $message = "Please delete the student attendance record first.";
    } else {
        // Use prepared statement for better security
        $deleteStudentSql = $conn->prepare("DELETE FROM students WHERE id = ?");
        $deleteStudentSql->bind_param("i", $id);

        if ($deleteStudentSql->execute()) {
            // Check if any rows were affected
            if ($deleteStudentSql->affected_rows > 0) {
                echo "Student deleted successfully!";
            } else {
                $message = "Error: Student with ID $id not found.";
            }
        } else {
            $message = "Error deleting student record: " . $deleteStudentSql->error;
        }

        $deleteStudentSql->close(); // Close the prepared statement

        // After deleting the records, reset the auto-increment value
        $resetAutoIncrementSql = "ALTER TABLE students AUTO_INCREMENT = 1";
        if ($conn->query($resetAutoIncrementSql) !== TRUE) {
            $message .= " Error resetting auto-increment value: " . $conn->error;
        }
    }

    $checkAttendanceSql->close(); // Close the prepared statement

    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student and Attendance Management</title>
    <link rel="stylesheet" href="delete.css">
</head>
<body>
    <h1>Student Attendance Management</h1>

    <form class="forum" method="post" action="./delete.php">
        <h3>Delete Student Record</h3>
        <label for="delete">Select Student ID to Delete:</label>
        <input type="number" name="delete" id="delete" placeholder="Enter Student Id" required>
        <p><?php echo $message ?></p>
        <input type="submit" id="submit" value="Delete Record">
    </form>

    <a href="./option.html">Back To Menu</a>
</body>
</html>
