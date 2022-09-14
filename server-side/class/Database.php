<?php
/**
 * Class to add user to database.
 * Can be used to:
 * 1. prepare user data to be encoded into json object
 * 2. Add a user to database
 */
class Database implements jsonserializable {
    private $username;
    private $fullName;
    private $dateOfBirth;
    private $email;
    
    /**
     * Constructor to initialise all fields
     *
     * @param [string] $username
     * @param [string] $fullName
     * @param [string] $dateOfBirth
     * @param [string] $email
     */
    public function __construct($username, $fullName, $dateOfBirth, $email) {
        $this->username = $username;
        $this->fullName = $fullName;
        $this->dateOfBirth = $dateOfBirth;
        $this->email = $email;
    }
  
    /**
     * Adds user to database
     *
     * @param [string] $file_name - name of file to write to
     * @param [string] $action - file action: r, w etc
     * @return void
     */
    public function addUser($file_name, $action) {
        // Open file for appending
        $file = fopen ($file_name, $action);
        // Write user json object to database
        fwrite ($file, json_encode($this->jsonserialize())."\n");
        // Close file
        fclose ($file);
    }

    /**
     * Returns this objects fields in json format
     *
     * @return void
     */
    public function jsonserialize(){
        return [
            'username' => $this->username,
            'fullName' => $this->fullName,
            'dateOfBirth' => $this->dateOfBirth,
            'email' => $this->email
        ];
    }
}

