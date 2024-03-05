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

    // Use prepared statement for better security
    $sql = $conn->prepare("DELETE FROM attendance WHERE student_id = ?");
    $sql->bind_param("i", $id);

    if ($sql->execute()) {
        echo "Student attendance record deleted successfully!";
    } else {
        echo "Error: " . $sql->error;
    }

    $sql->close(); // Close the prepared statement

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

    <form class="forum" method="post" action="./deleteattendance.php">
        <h3>Delete Student Attendance</h3>
        <label for="delete">Select Student ID to Delete:</label>
        <input type="number" name="delete" id="delete" placeholder="Enter Student Id" required>
        <input type="submit" id="submit" value="Delete Record">
    </form>

    <a href="./option.html">Back To Menu</a>
</body>
</html>
