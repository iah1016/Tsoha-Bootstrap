<?php

// (tilapäinen) etusivu
$routes->get('/', function() {
    HelloWorldController::index();
});

//------------------------------------------------------------ User
// login (get, post), logout
$routes->get('/login', function() {
    UserController::login();
});
$routes->post('/login', function() {
    UserController::handle_login();
});
// käyttäjä - uloskirjautuminen
$routes->post('/logout', function() {
    UserController::logout();
});

//------------------------------------------------------------ Song
// list, add, show, store(post)
$routes->get('/song', function() {
    SongController::index();
});
$routes->get('/song/new', function() {
    SongController::create();
});
$routes->get('/song/:id', function($id) {
    SongController::show($id);
});
$routes->post('/song', function() {
    SongController::store();
});
// edit, destroy
$routes->get('/song/:id/edit', function($id) {
    SongController::edit($id);
});
$routes->post('/song/:id/edit', function($id) {
    SongController::update($id);
});
$routes->post('/song/:id/destroy', function($id) {
    SongController::destroy($id);
});

//------------------------------------------------------------ Chant
// list, add, show, store(post)
$routes->get('/chant', function() {
    ChantController::index();
});
$routes->get('/chant/new', function() {
    ChantController::create();
});
$routes->get('/chant/:id', function($id) {
    ChantController::show($id);
});
$routes->post('/chant', function() {
    ChantController::store();
});
// edit, destroy
$routes->get('/chant/:id/edit', function($id) {
    ChantController::edit($id);
});
$routes->post('/chant/:id/edit', function($id) {
    ChantController::update($id);
});
$routes->post('/chant/:id/destroy', function($id) {
    ChantController::destroy($id);
});

//------------------------------------------------------------ Club
// list, add, show, store(post)
$routes->get('/club', function() {
    ClubController::index();
});
$routes->get('/club/new', function() {
    ClubController::create();
});
$routes->get('/club/:id', function($id) {
    ClubController::show($id);
});
$routes->post('/club', function() {
    ClubController::store();
});
// edit, destroy
$routes->get('/club/:id/edit', function($id) {
    ClubController::edit($id);
});
$routes->post('/club/:id/edit', function($id) {
    ClubController::update($id);
});
$routes->post('/club/:id/destroy', function($id) {
    ClubController::destroy($id);
});

// Sandbox
$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

// Preliminary layouts
$routes->get('/layout', function() {
    HelloWorldController::song_list();
});
$routes->get('/layout/show_song', function() {
    HelloWorldController::song_show();
});
$routes->get('/layout/edit_song', function() {
    HelloWorldController::song_edit();
});
