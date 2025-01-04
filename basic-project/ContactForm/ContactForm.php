<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Form</title>
    <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        session_start();
        require ("./validation.php");
        function sentMail($destGmail,$destName,$subject,$textMessageHTML,$painText,$status){
            require 'C:/xampp/htdocs/www/vendor/autoload.php';
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;                   // Enable verbose debug output
            $mail->isSMTP();                        // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com;';    // Specify main SMTP server
            $mail->SMTPAuth   = true;               // Enable SMTP authentication
            $mail->Username   = 'vathanakphy@gmail.com';     // SMTP username
            $mail->Password   = 'gtvp gfxm xbxc whjb';         // SMTP password
            $mail->SMTPSecure = 'tls';              // Enable TLS encryption, 'ssl' also accepted
            $mail->Port       = 587;  
            $mail->setFrom('vathanakphy@gmail.com', 'Phy Vathanak');           // Set sender of the mail
            // $mail->addAddress('mrvathanak1@gfg.net');           // Add a recipient
            $mail->addAddress($destGmail, $destName);   // Name is optional..    
            $mail->isHTML(true);                                  
            $mail->Subject = $subject;
            $mail->Body    = $textMessageHTML;
            $mail->AltBody = $painText;   
            if($status==true){
                $mail->send(); 
            }
        }
        $check = array("name","email","age","gender","phone");
        $status = " ";
        if(checkExitsVariable($check)){
            // foreach($_POST as $i=>&$value){
            //     $_POST[$i]=cleanInput($_POST[$i]);
            // }
            $database =  new mysqli("Localhost","root","","test");
            if($database->connect_error){
                die("Error connect database");
            }
            // if(isUsernameValid($_POST["name"]) && isNumberValid($_POST["age"]) && isEmailValid($_POST["email"])){
                $table = "user";
                function insert($table,$name,$email,$age,$gender,$phone){
                    global $database;
                    $insert = $database->prepare("INSERT INTO $table (name, email, age, gender, phone)VALUES (?, ?, ?, ?, ?);");
                    $insert->bind_param("ssiss", $name,$email,$age,$gender,$phone);
                    $insert->execute();
                    return ($insert->affected_rows)>0 ;
                }
                if(insert($table,$_POST["name"],$_POST["email"],$_POST["age"],$_POST["gender"],$_POST["phone"])){
                    $textMessageHTML = " <p> Thnak You for Complete Form  </p> <br>";
                    $textMessageHTML .= " Your Information :\n ";
                    $textMessageHTML .= " 
                    <ul>
                        <li>Name: " .$_POST['name'] ."</li>
                        <li>Email: " .$_POST['email'] ."/li>
                        <li>Age: " .$_POST['age'] ."</li>
                        <li>Gender: " .$_POST['gender'] ."</li>
                        <li>Phone: +855" .$_POST['phone'] ."</li>
                    </ul>               
                    ";
                    sentMail($_POST['email'],$_POST['name'],"Form Fill",$textMessageHTML," ",true);
                    $status = "Insert Success";
                }else{
                    $status = "Insert Faail";
                }
        }
    ?>
</head>
<body>
    <h2>User Information Form</h2>
    <!-- action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> -->
    <form method="POST"> 
        <label for="name">Username:</label><br>
        <input type="text" id="name" name="name" required><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="age">Age:</label><br>
        <input type="number" id="age" name="age" min="0" required><br><br>
        <label for="gender">Gender:</label><br>
        <input type="radio" id="male" name="gender" value="male" required>
        <label for="male">Male</label><br>
        <input type="radio" id="female" name="gender" value="female">
        <label for="female">Female</label><br>
        <input type="radio" id="other" name="gender" value="other">
        <label for="other">Other</label><br><br>
        <label for="phone">Phone Number:</label><br>
        <input type="tel" id="phone" name="phone" pattern="[0-9]{8}" required><br><br>
        <input type="submit" value="Submit">
        <?php
            echo $status;
        ?>
    </form>
</body>
</html>
