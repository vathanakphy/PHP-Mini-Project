<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login & Sign-Up</title>
   <link rel="stylesheet" href="./style_form.css">
   <style>
      /* Example styles for demonstration */
      .form {
         display: none;
      }
      .form.active {
         display: block;
      }  
   </style>
</head>
<body>
   <div class="forms-container">
      <div class="container">
         <div class="top-button">
            <button type="button" id="login-button" class="active">Login</button>
            <button type="button" id="signup-button">Sign Up</button>
         </div>
         <!-- Login Form -->
      <div class="form login-form active">
         <form method="POST">
            <h1>Login Form</h1>
            <input type="email" placeholder="Email Address" id="login-email" name="login-email" required>
            <input type="password" placeholder="Password" id="login-password" name="login-password" required>
            <a href="#" class="forgot-pass">Forgot password?</a>
            <button type="submit" id="login-submit" name="action" value="login">Login</button>
            <p>Not a member? <a href="#" id="signup-link">Sign up now</a></p>
         </form>
      </div>
      <!-- Sign-Up Form -->
      <div class="form signup-form">
         <form method="POST">
            <h1>Sign-Up Form</h1>
            <input type="text" placeholder="Full Name" id="signup-name" name="signup-name" required>
            <input type="email" placeholder="Email Address" id="signup-email" name="signup-email" required>
            <input type="password" placeholder="Password" id="signup-password" name="signup-password" required>
            <input type="password" placeholder="Confirm Password" id="signup-confirm-password" name="signup-confirm-password" required>
            <button type="submit" id="signup-submit" name="action" value="sign_up">Sign Up</button>
            <p>Already a member? <a href="#" id="login-link">Login now</a></p>
         </form>
      </div>

      </div>
   </div>

   <script>
      // JavaScript to toggle between forms
      const loginButton = document.getElementById("login-button");
      const signupButton = document.getElementById("signup-button");
      const loginForm = document.querySelector(".login-form");
      const signupForm = document.querySelector(".signup-form");
      const signupLink = document.getElementById("signup-link");
      const loginLink = document.getElementById("login-link");
      // Show Login Form
      function showLogin() {
         loginForm.classList.add("active");
         signupForm.classList.remove("active");
         loginButton.classList.add("active");
         signupButton.classList.remove("active");
      }
      // Show Sign-Up Form
      function showSignUp() {
         signupForm.classList.add("active");
         loginForm.classList.remove("active");
         signupButton.classList.add("active");
         loginButton.classList.remove("active");
      }
      // Event Listeners
      loginButton.addEventListener("click", showLogin);
      signupButton.addEventListener("click", showSignUp);
      signupLink.addEventListener("click", (e) => {
         e.preventDefault();
         showSignUp();
      });
      loginLink.addEventListener("click", (e) => {
         e.preventDefault();
         showLogin();
      });
   </script>
   <?php
      session_start();
      // session_set_cookie_params(24*60*60,"/");
      use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\Exception;
      $message = null;
      function sentMail($destGmail,$destName,$subject,$textMessageHTML,$painText,$status){
         require 'C:/xampp/htdocs/www/vendor/autoload.php';
         $mail = new PHPMailer(true);
         $mail->SMTPDebug = 2;                   // Enable verbose debug output
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
      $database =  new mysqli("Localhost","root","","test");
                  if($database->connect_error){
                     die("Error connect database");
                  }
      function checkExitsVariable($variable){
         if(is_array($variable)){
            foreach($variable as $var){
               if(!isset($_REQUEST[$var]) || $_REQUEST[$var] === ""){
                  return false;
               }
            }
         }else{
            if((!isset($_REQUEST[$variable]) || ($_REQUEST[$variable] === ""))){
               return false;
            }
         }
         return true;
      }
      function searchData($table,$email){
         global $database;
         $search = $database->prepare("SELECT * FROM $table WHERE email = ?;");
         $search->bind_param("s", $email);
         $search->execute();
         $data = $search->get_result();
         if ($data->num_rows > 0) {
            return $data->fetch_assoc();
         }
         return null;
      }  
      $row = 0;
      if(!empty($_POST['action'] )){
         if($_POST['action'] == 'sign_up'){
            $get_value = array("signup-name","signup-email","signup-password","signup-confirm-password");
            if(checkExitsVariable($get_value)){
               if($_POST['signup-password'] == $_POST['signup-confirm-password']){
                     $_SESSION['signup-name'] = $_POST['signup-name'];
                     $_SESSION['signup-email'] = $_POST['signup-email'];
                     $_SESSION['signup-password'] = $_POST['signup-password'];
                     $code = random_int(1000, 9999);
                     $message = " ";
                     $messageHTML = "<p>Your verifycation code is<b> $code<b></P>";
                     $_SESSION['code'] = $code;
                     header("Location: ./verify_email.php");
                     sentMail($_SESSION['signup-email'],$_SESSION['signup-name'],"Verification Code",$messageHTML,"",true);
                     exit();
               }else{
                  $message = "Confirm password is incorrect ";
               }
            }else{
               $message = "Sign Up fail....";
            }
         }else if($_POST['action'] == 'login'){
            $get_value = array("login-email","login-password");
            // if(checkExitsVariable($get_value)){
               $table = "user_data";
               $row = searchData($table,$_POST['login-email']);
               $mege = "username or password is worng";
               if( $row!=null && ($_POST["login-password"] == $row["password"])){
                  $_SESSION = $row;
                  header("Location: ./index.php");
                  exit();
               }else{
                  echo "<script type='text/javascript'>alert('$mege');</script>";
               }
            // }
         }
      }
   ?>
</body>
</html>
