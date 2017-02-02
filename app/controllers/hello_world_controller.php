<?php

//require 'app/models/song.php'; Composer käsittelee

class HelloWorldController extends BaseController {

    // (tilapäinen) etusivu
    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        // View::make('home.html');
        View::make('suunnitelmat/first.html');
    }

    // (tilapäinen) sisäänkirjautuminen
    public static function login() {
        View::make('suunnitelmat/login.html');
    }
    
    public static function sandbox() {
        $all_songs = Song::all();
        $first_song = Song::find(1);

        Kint::dump($all_songs);
        Kint::dump($first_song);
    }

//    public static function song_list() {
//        View::make('suunnitelmat/song_list.html');
//    }
//
//    public static function song_show() {
//        View::make('suunnitelmat/song_show.html');
//    }
//
//    public static function song_edit() {
//        View::make('suunnitelmat/song_edit.html');
//    }
//
//    public static function performer_list() {
//        View::make('suunnitelmat/performer_list.html');
//    }
//
//    public static function performer_show() {
//        View::make('suunnitelmat/performer_show.html');
//    }
//
//    public static function performer_edit() {
//        View::make('suunnitelmat/performer_edit.html');
//    }
//
//    public static function chant_list() {
//        View::make('suunnitelmat/chant_list.html');
//    }
//
//    public static function chant_show() {
//        View::make('suunnitelmat/chant_show.html');
//    }
//
//    public static function chant_edit() {
//        View::make('suunnitelmat/chant_edit.html');
//    }
//
//    public static function club_list() {
//        View::make('suunnitelmat/club_list.html');
//    }
//
//    public static function club_show() {
//        View::make('suunnitelmat/club_show.html');
//    }
//
//    public static function club_edit() {
//        View::make('suunnitelmat/club_edit.html');
//    }
}
