<?php

/**
 * User model class
 *
 * @author Ilja Häkkinen
 */
class User extends BaseModel {

    public $id, $username, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function authenticate($username, $password) {
        $query = DB::connection()->prepare(
                'SELECT * FROM SignedUser '
                . 'WHERE username = :username AND password = :password '
                . 'LIMIT 1'
        );
        $query->execute(array(
            'username' => $username,
            'password' => $password
        ));
        $result = $query->fetch();

        if ($result) {
            return new User(array(
                'id' => $result['id'],
                'username' => $result['username'],
                'password' => $result['password']
            ));
        } else {
            return null;
        }  
    }

    public static function find($id) {
        $query = DB::connection()->prepare(
                'SELECT * FROM SignedUser '
                . 'WHERE id = :id'
        );
        $query->execute(array('id' => $id));
        $result = $query->fetch();
        
        if ($result) {
            return new User(array(
                'id' => $result['id'],
                'username' => $result['username'],
                'password' => $result['password']
                ));
        } else {
            return null;
        }
    }

}
