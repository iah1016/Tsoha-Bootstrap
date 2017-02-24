<?php

class UserController extends BaseController {
    
    public static function login() {
        View::make('user/login.html', array(
            'transparent' => 'say no to white background!'));
    }

    public static function handle_login() {
        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if (!$user) {
            View::make('user/login.html', array(
                'error' => 'Incorrect username or password',
                'username' => $params['username'],
                'transparent' => 'say no to white background!'));
        } else {
            $_SESSION['user'] = $user->id;

            Redirect::to('/', array(
                'message' => 'Welcome back, ' . $user->username . '!',
                'transparent' => 'say no to white background!'));
        }
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array(
            'message' => 'You have successfully logout.',
            'transparent' => 'say no to white background!'));
    }

}
