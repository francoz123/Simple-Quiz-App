<?php
ini_set('display_errors', TRUE);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
// Set time zone
date_default_timezone_set("Australia/Sydney");

$responses = [
    400 => "Bad Request",
    404 => "Not Found",
    405 => "Method Not Allowed",
    500 => "Internal server error"
];

/**
 * Function to send json error object to a user
 *
 * @param [int] $code - HTTP error code
 * @param [string] $message - error message
 * @return void
 */
function send_error ($code, $message){
    global $responses;
    header($_SERVER['SERVER_PROTOCOL'] . ' '. $code . ' - ' . $responses[$code] . ': '. $message);
    $error = array("error" => $code . " - " . $responses[$code] . ": " . $message);
    print(json_encode($error));
}

// Add dependencies
require_once "./class/Database.php";
require_once "./class/Validator.php";

// Verify that request type is POST. If not, return error
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    send_error(400, "Only POST requests allowed.");
    die();
}elseif(!isset($_POST)) { // If no data sent through
    send_error(400, "No data provided. Request body cannot be empty. Please fill out all fields");
    die();
}else { // If fields are missing
    $fields = ["username", "fullName", "dateOfBirth", "email"];
    foreach ($fields as $value) {
        if (!isset($_POST[$value])){
            send_error(400, "Some fields missing. All fields must be provided.");
            die();
        }
    }
}

// Regex to validate name
$name_regex = "/^[a-zA-Z-']+\s+[a-zA-Z-']+$/";
// Regex to validate username
$username_regex_array = ["/[A-Z]/", "/[0-9]/", "/[~!@#$%\^&?*]/"];
// Regex to validate date
$date_regex = "/^((3[0-1]|[0-2][0-9])\/(0[13578]|1[02])|(30|[0-2][0-9])\/(0[469]|11)|(([0-1][0-9]|2[0-8])\/02))\/(1[0-9][0-9][0-9]|20[0-2][0-2])$/";
// Retrieve year from date of birth
$yearOfbirth = explode ("/", $_POST["dateOfBirth"])[2];
//check if leap year
if (intval ($yearOfbirth) % 4 == 0) {
    $date_regex = "/^((3[0-1]|[0-2][0-9])\/(0[13578]|1[02])|(30|[0-2][0-9])\/(0[469]|11)|([0-2][0-9]\/02))\/(1[0-9][0-9][0-9]|20[0-2][0-2])$/";
}
// Instantiate a validator object
$validator = new Validator();
// Validate fields
$validator->validateUsername("username", $username_regex_array);
$validator->validateName("fullName", $name_regex);
$validator->validateDOB("dateOfBirth", $date_regex);
$validator->validateEmail("email");


// Return timestamp from date of birth
$date_array = date_parse_from_format ("d/m/Y", $_POST["dateOfBirth"]);
// Get current timestamp
$age = time() - mktime (0, 0, 0, $date_array['month'], $date_array['day'], $date_array['year']);
// Convert timestamp to years and floor
$age = $age / (60*60*24*365);
$age = (int) $age;
// Instantiate Database object and call the add user method to add user to database
$db = new Database($_POST["username"], $_POST["fullName"], $_POST["dateOfBirth"], $_POST["email"]);
$db->addUser('users.txt', 'a'); 
// Shuffle username and append age
$password = [ 'password' => str_shuffle ($_POST['username']) . $age ];
// Send password to user
print(json_encode($password));








