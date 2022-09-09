<?php

class Validator {
    
    public function validateUsername($username, $regex_array){
        if (!isset($_POST[$username]) || strlen($_POST[$username]) == 0){
            send_error(400, "Username is required");
            die();
        }else{
            if (strlen($_POST[$username]) < 8 || strlen($_POST[$username]) > 20) {
                send_error(400, "Username must between 8 and 20 characters long.");
                die();
            }
        
            foreach ($regex_array as $value) {
                if (preg_match ($value, $_POST[$username]) == 0){
                    send_error(400, "Username must contain at least one upper case letter, at least one digit, and one special character.");
                    die();
                }
            }
        }
    }

    public function validateName($name, $regex){
        if (!isset($_POST[$name])  || strlen($_POST[$name]) == 0){
            send_error(400, "Full name required");
            die();
        }else{
            if (preg_match ($regex, $_POST[$name]) == 0){
                send_error(400, "Please provide your full name. Name can only contain letters and the characters \"-\" and \"-\".");
                die();
            }
        }
    }

    public function validateDOB($dob, $dob_regex){
        if (preg_match ("/\S+/", $_POST[$dob]) == 0){
            send_error(400, "Please enter your date of birth.");
            die();
        }else {
            
            if (preg_match ($dob_regex, $_POST[$dob]) == 0) {
                send_error(400, "Please make sure date of birth is in the format dd/mm/yyyy.");
                die();
            }
        }
    }

    public function validateEmail($field){
        if (!isset($_POST[$field])  || strlen($_POST[$field]) == 0){
            send_error(400, "Email required.");
            die();
        }else{
            // validation from https://stackoverflow.com/questions/13719821/email-validation-using-regular-expression-in-php
            if (preg_match ("/^[_a-z0-9A-Z-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $_POST[$field]) == 0) {
                send_error(400, "Please enter a valid email address.");
                die();
            }
        }
    }
}