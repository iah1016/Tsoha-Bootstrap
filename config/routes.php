<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/testi', function() {
    HelloWorldController::listview();
  });
  
  $routes->get('/testi/1', function() {
    HelloWorldController::show();
  });
  
  $routes->get('/testi/2', function() {
    HelloWorldController::edit();
  });