<?php
ini_set('display_errors', TRUE);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

date_default_timezone_set("Australia/Sydney");

$responses = [
    400 => "Bad Request",
    404 => "Not Found",
    405 => "Method Not Allowed",
    500 => "Internal server error"
];

$protocol = $_SERVER["SERVER_PROTOCOL"];
function send_error ($code, $message){
    global $responses;
    header($_SERVER['SERVER_PROTOCOL'] . ' '. $code . ' - ' . $responses[$code] . ': '. $message);
    $error = array("error" => $code . " - " . $responses[$code] . ": " . $message);
    print(json_encode($error));
}

$time = date("h:i:s");
$date = date("Y-m-d");

require_once "./class/Database.php";
require_once "./class/Validator.php";

/* $name = $_POST["name"];
$age = $_POST["age"];
$email = $_POST["email"];
$phone = $_POST["phone"]; */
//print(count($_POST));
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    send_error(400, "Only POST requests allowed.");
    die();
}elseif(!isset($_POST)) {
    send_error(400, "No data provided. Request body cannot be empty.");
    die();
}else {
    $fields = ["username", "fullName", "dateOfBirth", "email"];
    foreach ($fields as $value) {
        if (!isset($_POST[$value])){
            send_error(400, "Incomplete POST body. All fields must be provided.");
            die();
        }
    }
}
   
$name_regex = "/^[a-zA-Z-']+\s+[a-zA-Z-']+$/";
$username_regex_array = ["/[A-Z]/", "/[0-9]/", "/[~!@#$%\^&?*]/"];
$date_regex = "/^(3[0-1]|[0-2][1-9])[-\/](0[1-9]|1[0-2])[\/-](1[0-9][0-9][0-9]|20[0-2][0-2])$/";

$validator = new Validator();

$validator->validateUsername("username", $username_regex_array);
$validator->validateName("fullName", $name_regex);
$validator->validateDOB("dateOfBirth", $date_regex);
$validator->validateEmail("email");

$date_array = date_parse_from_format ("d/m/Y", $_POST["dateOfBirth"]);
$age = time() - mktime (0, 0, 0, $date_array['month'], $date_array['day'], $date_array['year']);
$age = $age / (60*60*24*365);
$age = (int) $age;
$db = new Database($_POST["username"], $_POST["fullName"], $_POST["dateOfBirth"], $_POST["email"]);
$db->addUser('users.txt', 'a'); 
$password = [ 'password' => str_shuffle ($_POST['username']) . $age ];
print(json_encode($password));









