<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);

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
    $sql = $conn->prepare("INSERT INTO students (name, email) VALUES (?, ?)");
    $sql->bind_param("ss", $name, $email);

    if ($sql->execute()) {
        echo "Student added successfully!";
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
    <title>Student Registration Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="forum" method="post" action="">
        <h1>Student Registration Form</h1>
        <h3>Enter Your Details</h3>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter Your Full Name" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter Your Email Here" required>
        <input type="submit" id="submit" value="Register">
        <a href="../index.html">Back To Main Menu</a>
    </form>
</body>
</html>
