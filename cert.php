<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "certificate_organizer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"] ?? "";
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $action = $_POST["action"];

    if ($action === "register") {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "Registration successful";
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif ($action === "login") {
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($_POST["password"], $user["password"])) {
                echo "Login successful";
            } else {
                echo "Invalid password";
            }
        } else {
            echo "User not found";
        }
    }
}

$conn->close();
?>
