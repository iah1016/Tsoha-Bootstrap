<?php

// etusivu

$routes->get('/', function() {
    HelloWorldController::index();
});

// sisäänkirjautuminen

$routes->get('/login', function() {
    HelloWorldController::login();
});

// kannatuslaulut (lisää, listaa, näytä)

$routes->post('/song', function() {
    SongController::store();
});

$routes->get('/song/new', function() {
    SongController::create();
});

$routes->get('/song', function() {
    SongController::index();
});

$routes->get('/song/:id', function($id) {
    SongController::show($id);
});

// hiekkalaatikko

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

// suunnitelmat näkymille

$routes->get('/layout', function() {
    HelloWorldController::song_list();
});

$routes->get('/layout/show_song', function() {
    HelloWorldController::song_show();
});

$routes->get('/layout/edit_song', function() {
    HelloWorldController::song_edit();
});
