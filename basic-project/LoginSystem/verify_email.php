<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <link rel="stylesheet" href="style_ver.css">
    <?php 
        session_start();
        if (empty($_SESSION)) {
            header("Location: ./form.php?ref=" . $_SERVER["PHP_SELF"]);
            exit();
        }
        $database =  new mysqli("Localhost","root","","test");
        if($database->connect_error){
            die("Error connect database");
        }
        function insert($table,$name,$email,$password){
            global $database;
            $insert = $database->prepare("INSERT INTO $table (name, email, password)VALUES (?, ?, ?);");
            $insert->bind_param("sss", $name,$email,$password);
            $insert->execute();
            return ($insert->affected_rows)>0 ;
        }
        if(!empty($_POST['action'])){
            $get_code = 1000*(int)$_POST['code-1']+100*(int)$_POST['code-2']+10*(int)$_POST['code-3']+(int)$_POST['code-4'];  
            if($_SESSION['code'] === $get_code){
                $table = "user_data";
                if(insert($table,$_SESSION['signup-name'],$_SESSION['signup-email'],$_SESSION['signup-password'])){
                    session_destroy();
                    header("Location: ./index.php");
                }
            }
        }
    ?>
</head>
<body>
    <div class="verification-container">
    <div class="verification-box">
        <h1>Enter Verification Code</h1>
        <p>Please enter the 4-digit verification code sent to your email.</p>
        <form method="POST">
            <div class="code-inputs">
                <input type="number" maxlength="1" name="code-1" class="code-input" required>
                <input type="number" maxlength="1" name="code-2" class="code-input" required>
                <input type="number" maxlength="1" name="code-3" class="code-input" required>
                <input type="number" maxlength="1" name="code-4" class="code-input" required>
            </div>
            <button type="submit" class="verify-button" name="action" value="get-code">Verify</button>
        </form>
        <p class="resend-text">Didn’t receive the code? <a href="#" type="submit" class="resend-link" name="verify-code" value="code">Resend Code</a></p>
        </div>
    </div>
</body>
</html>
