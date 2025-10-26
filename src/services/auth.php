<?php
// Use /tmp for writable storage on Render
define('USERS_FILE', '/tmp/users.json');

function getUsers()
{
    // Ensure the file exists
    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, json_encode([]));
    }

    // Read file safely
    $data = file_get_contents(USERS_FILE);
    $users = json_decode($data, true);

    // If decode fails or returns null, return an empty array
    if (!is_array($users)) {
        $users = [];
    }

    return $users;
}

function saveUsers($users)
{
    // Safely save user data to /tmp
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function registerUser($email, $password)
{
    $users = getUsers();

    // Check if email already exists
    foreach ($users as $u) {
        if ($u['email'] === $email) {
            return ['success' => false, 'message' => 'Email already registered.'];
        }
    }

    // Add new user
    $users[] = [
        'email' => $email,
        'password' => $password
    ];

    saveUsers($users);

    return ['success' => true, 'user' => ['email' => $email]];
}

function loginUser($email, $password)
{
    $users = getUsers();

    foreach ($users as $u) {
        if ($u['email'] === $email && $u['password'] === $password) {
            return ['success' => true, 'user' => $u];
        }
    }

    return ['success' => false, 'message' => 'Invalid credentials.'];
}
