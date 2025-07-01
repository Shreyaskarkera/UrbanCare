<?php

// require_once __DIR__ . '/../connect.php';
require_once 'connection.php';

function insertUser($name, $email, $password, $phone_no)
{
    $conn = db_connect();

    // Prepare the SQL statement
    $sql = "INSERT INTO users (name, email, password, phone_no, role_id) 
            VALUES ('$name', '$email', '$password', '$phone_no', 1)";

    // Execute the query
    $success = $conn->query($sql) === TRUE;

    db_close($conn);  // Close the connection after the query

    return $success;  // Return true or false depending on query success
}

function getUserByEmailPassword($email, $password, &$errorMessage) {
    $conn = db_connect();

    $sql = "SELECT id, name, role_id, password, is_active FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $errorMessage = "Invalid email or password.";
        return null;
    }

    $user = $result->fetch_assoc();
    $stmt->close();
    db_close($conn);

    // 🔹 Verify the password using password_verify()
    if (!password_verify($password, $user['password'])) {
        $errorMessage = "Invalid email or password.";
        return null;
    }

    // 🔹 Check if user is blocked
    if ($user['is_active'] == 0) {
        header("Location: block_page.php");
        exit();
    }

    return $user; // Login successful
}



function getRoleById($role_id) {

    $conn = db_connect();

    // Prepare the SQL query to fetch role details based on role_id
    $sql = "SELECT id, name FROM role WHERE id = '$role_id'"; 

    // Execute the query
    $result = $conn->query($sql);

    // Fetch and return the role data if found
    $role = ($result->num_rows > 0) ? $result->fetch_assoc() : null;

    db_close($conn);  // Close the connection after the query

    return $role;
}


function getUserById($id)
{
    $conn = db_connect();

    $sql = "SELECT id, name, role_id FROM users WHERE id = '$id' "; 

    // Execute the query
    $result = $conn->query($sql);

    // Fetch and return the user data if found
    $user = ($result->num_rows > 0) ? $result->fetch_assoc() : null;

    db_close($conn);  // Close the connection after the query

    return $user;
}

?>