<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <?php
        session_start();
        if(empty($_SESSION)){
            header("Location: ./form.php?ref=./index.php");
            exit();
        }
        // setcookie("username","Nak",time()-2*24*60*60,'/');
        session_destroy();
        header("Location: ./form.php");
        exit();
    ?>
</body>
</html>