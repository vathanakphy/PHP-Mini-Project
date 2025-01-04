<?php
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
    function cleanInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data,ENT_QUOTES, 'UTF-8');
        return $data;
    }   
    function isUsernameValid(&$userName){
        $userName = trim($userName);
        if (!preg_match("/^[a-zA-Z0-9_]*$/", $userName)) {
            $errorMsg = "Only alphabets, numbers, and underscores are allowed for User Name";
        }else{
                echo $userName;
        }
    }
    function isNumberValid(&$num){
        if (!preg_match("/^[0-9]*$/", $num)) {
            $errorMsg = "Only numeric values are allowed!!";
        }else{
                echo $num;
        }
    }
    function isEmailValid(&$emailID){
        $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  
        if (!preg_match($pattern, $emailID) ){  
            $errorMsg = "Invalid Email ID format";
        }else{
            echo $emailID;
        }
    }
?>