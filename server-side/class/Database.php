<?php
class Database implements jsonserializable {
    private $username;
    private $name;
    private $dob;
    private $email;
    
    public function __construct($username, $name, $dob, $email) {
        $this->username = $username;
        $this->name = $name;
        $this->dob = $dob;
        $this->email = $email;
    }
  
    public function addUser($file_name, $action)    {
        $file = fopen ($file_name, $action);
        $st = json_encode($this->jsonserialize());
        $st = $st . "\n";
        fwrite ($file, json_encode($this->jsonserialize())."\n");
        //fwrite ($file, $st);
        //fwrite ($file, );
        fclose ($file);
    }

    public function jsonserialize(){
        return [
            'username' => $this->username,
            'name' => $this->name,
            'dob' => $this->dob,
            'email' => $this->email
        ];
    }
}
/* 
$db = new Database("user1", "francis o", "12/12/1980", "ogdshd@mail.com");

print_r(json_encode($db->jsonserialize()));
?>
 */
