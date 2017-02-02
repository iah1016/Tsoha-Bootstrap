<?php

// (tilapäinen) etusivu
$routes->get('/', function() {
    HelloWorldController::index();
});

// (tilapäinen) sisäänkirjautuminen
$routes->get('/login', function() {
    HelloWorldController::login();
});

// kannatuslaulut (lisää tietokantaan ja lisää/listaa/näytä-näkymät)
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

// kannatuslaulut - muokkaa-näkymä (ei vielä toteutusta viikolla 3)
$routes->get('/song/:id/edit', function($id) {
    SongController::edit($id);
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
