<?php

// (tilapäinen) etusivu
$routes->get('/', function() {
    HelloWorldController::index();
});

// sisäänkirjautuminen
$routes->get('/login', function() {
    UserController::login();
});

$routes->post('/login', function() {
    UserController::handle_login();
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

// kannatuslaulut - muokkaa/tuhoa-näkymät
$routes->get('/song/:id/edit', function($id) {
    SongController::edit($id);
});
$routes->post('/song/:id/edit', function($id) {
    SongController::update($id);
});
$routes->post('/song/:id/destroy', function($id) {
    SongController::destroy($id);
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
