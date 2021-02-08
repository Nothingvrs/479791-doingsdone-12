<?php
include 'functions.php';
$connect = connect();
$users = getUsersInfo($connect);

$required_fields = ['email', 'password', 'name'];
$errors = [];

if (isset($_POST['submit'])) {
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        };
    };
  
    if (!empty($_POST['email'])) {
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
            $errors['email'] = 'Введите корректный Email';
        };

        foreach ($users as $user) {
            if ($user['e_mail'] == $_POST['email']) {
                $errors['email'] = 'Пользователь с этим Email уже зарегестрирован';
            };
        };
    };

    if (!empty($_POST['password'])) {
        $hashPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    };
    
    if (empty($errors)) {
        addUser($connect, $_POST['name'], $_POST['email'], $hashPassword);
        header('Location: index.php');
        exit;
    };
};

$pageContent = include_template('registerForm.php', ['errors' => $errors]);
$layoutContent = include_template('layout.php', ['content' => $pageContent, 'title' => "Дела в порядке"]);

print($layoutContent);
