<?php
    session_start();
//route
    if($_SESSION['success']){
        echo "<script>alert('Задача успешно добавлена');</script>";
        unset($_SESSION['success']);
    } elseif (isset($_SESSION['login']) && $_SESSION['login'] == false) {
        echo "<script>alert('Неверный пароль или имя пользователя');</script>";
        unset($_SESSION['login']);
    } elseif (isset($_SESSION['change']) && $_SESSION['change'] == false) {
        echo "<script>alert('Для изменения задач авторизируйтесь под админом');</script>";
        unset($_SESSION['change']);
    }
    $action = 'showTask';
    include "controller.php";

?>