<?php
define('USERS_FILE', __DIR__ . '/users.json');

function getUsers() {
    if (!file_exists(USERS_FILE)) file_put_contents(USERS_FILE, json_encode([]));
    return json_decode(file_get_contents(USERS_FILE), true);
}

function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function registerUser($email, $password) {
    $users = getUsers();
    foreach ($users as $u) {
        if ($u['email'] === $email) return ['success' => false, 'message' => 'Email already registered.'];
    }
    $users[] = ['email' => $email, 'password' => $password];
    saveUsers($users);
    return ['success' => true, 'user' => ['email' => $email]];
}

function loginUser($email, $password) {
    $users = getUsers();
    foreach ($users as $u) {
        if ($u['email'] === $email && $u['password'] === $password) {
            return ['success' => true, 'user' => $u];
        }
    }
    return ['success' => false, 'message' => 'Invalid credentials.'];
}
