<?php
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];
$old_data = [];

function validatePassword($password)
{
    global $errors;
    if (strlen($password) !== 8) {
        $errors['password'] = "Password must be exactly 8 characters.";
    }
    if (!preg_match('/^[a-z0-9_]+$/', $password)) {
        $errors['password'] = "Password can only contain lowercase letters, numbers, and underscores.";
    }
}

if (empty($email)) {
    $errors['email'] = "Email is required.";
} else {
    $old_data['email'] = $email;
}

if (empty($password)) {
    $errors['password'] = "Password is required.";
} else {
    validatePassword($password);
}

if ($errors) {
    $errors = json_encode($errors);
    $url = "Location: login.php?errors={$errors}";
    if ($old_data) {
        $old_data = json_encode($old_data);
        $url .= "&old_data={$old_data}";
    }
    header($url);
    exit();
}

$file = file("Data.txt");
$user_valid = false;

foreach ($file as $line) {
    $line = trim($line);
    $fields = explode(":", $line);

    foreach ($fields as $index => $value) {
        if ($index === 2) {
            $file_email = $value;
        } elseif ($index === 3) {
            $file_password = $value;
        }
    }

    if ($email === $file_email && $password === $file_password) {
        $user_valid = true;
        break;
    }
}

if ($user_valid) {
    $_SESSION['email'] = $email;
    $_SESSION['login'] = true;
    header('Location: welcome.php');
} else {
    $errors['password'] = "Invalid email or password.";
    $errors = json_encode($errors);
    $url = "Location: login.php?errors={$errors}&old_data=" . urlencode(json_encode($old_data));
    header($url);
}