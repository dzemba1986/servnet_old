<?php
//if(! defined('NESTED'))
//  die('Nieprawidłowe wywołanie skryptu!');
require('../path.php');

if(! defined('PATH_MAIN_CONF'))
  die('Nieprawidłowy nadrzędny plik path.php!');

if( defined('PATH_LISTA_CONF'))
  exit();

// Defining Path constants
define('PATH_LISTA_CONF', true);

