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

// testejä

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

//  $routes->get('/testi', function() {
//    HelloWorldController::song_list();
//  });
//  
//  $routes->get('/testi/show_song', function() {
//    HelloWorldController::song_show();
//  });
//  
//  $routes->get('/testi/edit_song', function() {
//    HelloWorldController::song_edit();
//  });
//  
//  $routes->get('/testi/performers', function() {
//    HelloWorldController::performer_list();
//  });
//  
//  $routes->get('/testi/show_performers', function() {
//    HelloWorldController::performer_show();
//  });
//  
//  $routes->get('/testi/edit_performers', function() {
//    HelloWorldController::performer_edit();
//  });
//  
//  $routes->get('/testi/chants', function() {
//    HelloWorldController::chant_list();
//  });
//  
//  $routes->get('/testi/show_chants', function() {
//    HelloWorldController::chant_show();
//  });
//  
//  $routes->get('/testi/edit_chants', function() {
//    HelloWorldController::chant_edit();
//  });
//
//  $routes->get('/testi/clubs', function() {
//    HelloWorldController::club_list();
//  });
//  
//  $routes->get('/testi/show_clubs', function() {
//    HelloWorldController::club_show();
//  });
//  
//  $routes->get('/testi/edit_clubs', function() {
//    HelloWorldController::club_edit();
//  });