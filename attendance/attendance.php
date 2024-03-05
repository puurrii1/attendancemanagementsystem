<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_POST['studentId'];

    // Check if attendance record already exists for the student on the current date
    $checkAttendanceSql = $conn->prepare("SELECT * FROM attendance WHERE student_id = ? AND DATE(attendance_timestamp) = CURDATE()");
    $checkAttendanceSql->bind_param("i", $studentId);
    $checkAttendanceSql->execute();
    $checkResult = $checkAttendanceSql->get_result();

    if ($checkResult->num_rows > 0) {
        $message = "Error: Attendance record already exists for this student today.";
    } else {
        // Insert the new attendance record
        $insertAttendanceSql = $conn->prepare("INSERT INTO attendance (student_id, attendance_timestamp) VALUES (?, CURDATE())");
        $insertAttendanceSql->bind_param("i", $studentId);

        if ($insertAttendanceSql->execute()) {
            $message = "Attendance marked successfully!";
        } else {
            $message = "Error marking attendance: " . $insertAttendanceSql->error;
        }

        $insertAttendanceSql->close(); // Close the prepared statement
    }

    $checkAttendanceSql->close(); // Close the prepared statement
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Attendance</title>
    <link rel="stylesheet" href="style.css">
    <style>
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4caf50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Record Attendance</h1>

    <form method="post" action="">
        <label for="studentId">Student ID:</label>
        <input type="text" id="studentId" name="studentId" required>
        <input type="submit" value="Mark Attendance">
        <br>
        <p><?php echo $message; ?></p>
        <a href="../index.html">Back To Main Menu</a>  
    </form>
</body>
</html>
