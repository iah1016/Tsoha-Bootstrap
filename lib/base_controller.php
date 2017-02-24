<?php

class BaseController {

    public static function index() {
        View::make('home.html', array(
            'transparent' => 'say no to white background!'));
    }
    
    public static function get_user_logged_in() {
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            $user = User::find($user_id);

            return $user;
        }

        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('message' => 'Please login first!'));
        }
    }

}
