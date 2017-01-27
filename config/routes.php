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
  
  $routes->get('/login', function() {
    HelloWorldController::login();
  });