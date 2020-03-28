<?php
session_start();
function go_home_page(){
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $file = 'index.php';
    header("Location: http://$host$uri/$file");
}

include "model.php";
$model = new Model;
if (isset($_POST['action'])){
    $action = $_POST['action'];
}
switch($action) {
    case "setOrder":
        $model->setOrder();
        break;
    case "showTask":
        $tasks = $model->getTask();
        
        //pagination options
        $num = 3;
        $page = $_GET['page'];
        for ($index = 0; isset($tasks[$index]); $index++) {}
        $total = intval(($index - 1) / $num) + 1;
        $page = intval($page);
        if (empty($page) or $page < 0)
            $page = 1;
        if ($page > $total)
            $page = $total;
        $start = $page * $num - $num;
        
        include "view.php";
        break;
    case "login":
        $model->login();
        go_home_page();
        break;
    case "logout":
        $model->logout();
        go_home_page();
        break;
    case "addTask":
        $model->addTask();
        go_home_page();
        break;
    case "getTaskInfo":
        echo json_encode($model->getTaskInfo());
        break;
    case "changeTask":
        if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin'){
            $model->changeTask();
        } else {
            $_SESSION['change'] = false;
        }
        go_home_page();
        break;
}
?>