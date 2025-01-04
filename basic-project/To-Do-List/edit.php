<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style-list.css">
    <title>Edit</title>
    <?php 
        session_start();
        $database = new mysqli("Localhost", "root", "", "test");
        if ($database->connect_error) {
            die("Error connect database");
        }
        function searchData($id){
            global $database;
            $search = $database->prepare("SELECT * FROM list WHERE id = ?;");
            $search->bind_param("i", $id);
            $search->execute();
            $data = $search->get_result();
            if ($data->num_rows > 0) {
                return $data->fetch_assoc();
            }
            return null;
        }
        function checkExitsVariable($variable){
            if (is_array($variable)) {
                foreach ($variable as $var) {
                    if (!isset($_REQUEST[$var]) || $_REQUEST[$var] === "") {
                        return false;
                    }
                }
            } else {
                if ((!isset($_REQUEST[$variable]) || ($_REQUEST[$variable] === ""))) {
                    return false;
                }
            }
            return true;
        }
        if($_SESSION['edit_id']!=null){
            $task = searchData($_SESSION['edit_id']);
        }else{
            header("Location: ./list.php");
            exit();
        }
        if (isset($_REQUEST["editTask"])) {
            $edit = $database->prepare("UPDATE list SET task = ? WHERE id = ? ;");
            $edit->bind_param("si",$_POST["edit-task"],$_SESSION['edit_id']);
            $edit->execute();
            header("Location: ./list.php");
            $_SESSION['edit_id'] = null;
            exit();
        }
    ?>
</head>
<body>
    <div class="container">
        <form method="POST" class="add-task">
            <input type="text" placeholder="Write a task..." value= "<?php echo $task['task']; ?>" id="task-input" name="edit-task">
            <button id="add-task-btn" type="submit" name="editTask">Submit</button>
        </form>
    </div>
</body>
</html>