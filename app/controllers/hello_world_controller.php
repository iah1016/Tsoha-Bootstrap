<?php

//require 'app/models/song.php'; Composer käsittelee

class HelloWorldController extends BaseController {

    // (tilapäinen) etusivu
    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        // View::make('home.html');
        View::make('suunnitelmat/first.html', array(
            'transparent' => 'say no to white background!'));
    }

    // (tilapäinen) sisäänkirjautuminen
    public static function login() {
        View::make('suunnitelmat/login.html');
    }
    
    // hiekkalaatikko
    public static function sandbox() {
        $test_song = new Song(array(
           'name' => 'diipadaapa',
            'written_by' => 'c',
            'year' => '200X',
            'country' => 'UK',
            'genre' => '' 
        ));
//        $errors = $test_song->errors();
//        Kint::dump($errors);
        
//        $all_songs = Song::all();
//        $first_song = Song::find(1);
//
//        Kint::dump($all_songs);
//        Kint::dump($first_song);
    }

    
    // suunnitelmat näkymille
    public static function song_list() {
        View::make('suunnitelmat/song_list.html');
    }

    public static function song_show() {
        View::make('suunnitelmat/song_show.html');
    }

    public static function song_edit() {
        View::make('suunnitelmat/song_edit.html');
    }

}
