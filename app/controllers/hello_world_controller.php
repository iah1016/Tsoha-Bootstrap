<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        // View::make('home.html');
        echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        View::make('helloworld.html');
        }

    public static function listview(){
        View::make('suunnitelmat/list.html');
    }

    public static function show() {
        View::make('suunnitelmat/show.html');
    }

    public static function edit() {
        View::make('suunnitelmat/edit.html');
    }

}
