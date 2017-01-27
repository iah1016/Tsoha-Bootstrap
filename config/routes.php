<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/testi', function() {
    HelloWorldController::song_list();
  });
  
  $routes->get('/testi/show_song', function() {
    HelloWorldController::song_show();
  });
  
  $routes->get('/testi/edit_song', function() {
    HelloWorldController::song_edit();
  });
  
  $routes->get('/testi/performers', function() {
    HelloWorldController::performer_list();
  });
  
  $routes->get('/testi/show_performers', function() {
    HelloWorldController::performer_show();
  });
  
  $routes->get('/testi/edit_performers', function() {
    HelloWorldController::performer_edit();
  });
  
  $routes->get('/testi/chants', function() {
    HelloWorldController::chant_list();
  });
  
  $routes->get('/testi/show_chants', function() {
    HelloWorldController::chant_show();
  });
  
  $routes->get('/testi/edit_chants', function() {
    HelloWorldController::chant_edit();
  });

  $routes->get('/testi/clubs', function() {
    HelloWorldController::club_list();
  });
  
  $routes->get('/testi/show_clubs', function() {
    HelloWorldController::club_show();
  });
  
  $routes->get('/testi/edit_clubs', function() {
    HelloWorldController::club_edit();
  });
  
  $routes->get('/login', function() {
    HelloWorldController::login();
  });