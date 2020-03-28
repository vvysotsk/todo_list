<?php
    session_start();
    Class Model {
        private $db;
        
        public function __construct() {
            $servername = "localhost";
            $username = "admin";
            $password = "pass";
            $database = "test";

            // Create connection
            $this->db = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($this->db->connect_error) {
                die("Connection failed: " . $this->db->connect_error);
            }
        }
        
        public function getTask(){
            $result = $this->db->query("SELECT * from tasks ".$_SESSION['order'])->fetch_all(MYSQLI_ASSOC);
            return $result;
        }
        public function setOrder(){
            if ($_POST['order_by']){
                
                if ($_SESSION['order_by'] == $_POST['order_by'] && $_SESSION['direction'] == $_POST['direction']){
                    $_SESSION['direction'] = 'DESC';
                } else {
                    $_SESSION['order_by'] = $_POST['order_by'];
                    $_SESSION['direction'] = $_POST['direction'];
                }
                $_SESSION['order'] = "ORDER BY ".$_SESSION['order_by']." ".$_SESSION['direction'];
            }
        }

        public function login(){
            $login = $_POST['login']; 
            $pass = $_POST['passwd'];
            $result = $this->db->query("SELECT * from users WHERE login='admin'")
                    ->fetch_array(MYSQLI_ASSOC);
            if ($login == $result['login'] && $pass == $result['passwd']){
                $this->db->query("UPDATE users SET status='1' WHERE login='admin'");
                $_SESSION['user'] = 'admin';
                $_SESSION['login'] = true;
            } else {
                $_SESSION['login'] = false;
            }
        }
        
        public function logout(){
            $login = $_SESSION['user'];
            $this->db->query("UPDATE users SET status='0' WHERE login='$login'");
            session_unset();
            session_destroy();
            $_SESSION = array();
        }
        
        public function addTask(){
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $description = htmlspecialchars($_POST['description']);
            if ($this->db->query("INSERT INTO tasks (`username`, `email`, `description`) VALUES ('$username', '$email', '$description')"))
                $_SESSION['success'] = true;
        }
        
        public function getTaskInfo(){
            $task_id = $_POST['task_id'];
            $result = $this->db->query("SELECT * from tasks WHERE id='$task_id'")->fetch_object();
            return $result;
        }
        
        public function changeTask(){
            $task_id = $_POST['task_id'];
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $description = htmlspecialchars($_POST['description']);
            $status = $_POST['status'];
            $changed = '0';
            
            $old_descr = $this->db->query("SELECT description FROM tasks WHERE id='$task_id'")->fetch_row();
            if (strcmp($old_descr[0], $description) !== 0)
                $changed = '1';
            $this->db->query("UPDATE tasks SET username='$username', email='$email', "
                    . "description='$description', status='$status', changed=$changed WHERE id='$task_id'");
        }
    }
?>