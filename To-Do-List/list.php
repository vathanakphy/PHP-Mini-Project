<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="stylesheet" href="./style-list.css">
    <?php
    session_start();
    $database = new mysqli("Localhost", "root", "", "test");
    if ($database->connect_error) {
        die("Error connect database");
    }
    function insert($taskList){
        global $database;
        $insert = $database->prepare("INSERT INTO list (task) VALUES (?)");
        $insert->bind_param("s", $taskList);
        $insert->execute();
        return ($insert->affected_rows) > 0;
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
    if (checkExitsVariable("action")) {
        if (insert($_POST['task-input'])) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } 
    if(checkExitsVariable("done")){
        if($_GET["done"] !=null){
            $done = $database->prepare("UPDATE list SET status = 0 WHERE id = ? ;");
            $done->bind_param("i", $_GET["done"]);
            $done->execute();
            $_GET["done"] = null;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
    if(checkExitsVariable("return")){
        if($_GET["return"] !=null){
            $return = $database->prepare("UPDATE list SET status = 1  WHERE id = ? ;");
            $return->bind_param("i", $_GET["return"]);
            $return->execute();
            $_GET["return"] = null;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
    if(checkExitsVariable("edit")){
        if($_GET["edit"] !=null){
            $_SESSION['edit_id'] = $_GET["edit"];
            $_GET["edit"] = null;
            header("Location: ./edit.php");
            exit();
        }
    }
    if(checkExitsVariable("delete")){
        if($_GET["delete"] !=null){
            $delete = $database->prepare("DELETE FROM list WHERE id = ? ");
            $delete->bind_param("i", $_GET["delete"]);
            $delete->execute();
            $_GET["delete"] = null;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
    ?>
</head>
<?php
$curent_page = $_SERVER["PHP_SELF"];
function doneTask($value){
    global $curent_page;
    echo " <a href=";
    echo "$curent_page" . "?done=$value";
    echo ' class="return">Done</a>';
}
function returnTask($value){
    global $curent_page;
    echo " <a href=";
    echo "$curent_page" . "?return=$value";
    echo ' class="return">Return</a>';
}
function editTask($value){
    global $curent_page;
    echo " <a href=";
    echo "$curent_page" . "?edit=$value";
    echo ' class="edit">Edit</a>';
}
function deleteTask($value){
    global $curent_page;
    echo " <a href=";
    echo "$curent_page" . "?delete=$value";
    echo ' class="delete">Delete</a>';
}
function todo_list($id, $list, $date){
    echo "
        <tr>
            <td>$id</td>
            <td>$list</td>
            <td>$date</td>
        ";
    echo '<td>';
    doneTask($id);
    editTask($id);
    deleteTask($id);
    echo '
        </td>
    </tr>';
}
function done_list($id, $list, $date){
    echo "
        <tr>
            <td>$id</td>
            <td>$list</td>
            <td>$date</td>
        ";
    echo '  <td>';
    returnTask($id);
    editTask($id);
    deleteTask($id);
    echo '
            </td>
        </tr>';
}
?>

<body>
    <div class="container">
        <form method="POST" class="add-task">
            <input type="text" placeholder="Write a task..." id="task-input" name="task-input">
            <button id="add-task-btn" type="submit" name="action" value="add-task">Add</button>
        </form>
        <div class="todos-section">
            <h3>Todos:</h3>
            <table class="task-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Task</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="todos">
                    <!-- Todo tasks will go here -->
                    <?php
                        $result = $database->query("SELECT * FROM list WHERE status = 1 ;");
                        while($row = $result->fetch_assoc()){
                            todo_list($row['id'],$row['task'], $row['date']);
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="done-section">
            <h3>Done:</h3>
            <table class="task-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Task</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="done-tasks">
                    <?php
                        $result = $database->query("SELECT * FROM list WHERE status = 0 ;");
                        while($row = $result->fetch_assoc()){
                            done_list($row['id'],$row['task'], $row['date']);
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>