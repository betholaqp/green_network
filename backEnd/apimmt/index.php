<?php

require 'vendor/autoload.php';
//directorio del proyecto
define("PROJECTPATH", dirname(__DIR__).'/apimmt');
//echo PROJECTPATH;
//directorio app
define("APPPATH", PROJECTPATH . '/App');
define("IP", $_SERVER['SERVER_NAME']);
//autoload con namespaces

function autoload_classes($class_name)
{
    $filename = PROJECTPATH . '/' . str_replace('\\', '/', $class_name) .'.php';
    if(is_file($filename))
    {
        include_once $filename;
    }
}
spl_autoload_register('autoload_classes');
/////DESCOMENTAR ESTO PARA LANZARLO A PRODUCCION
/*$c = new Slim\Container();
$c['errorHandler'] = function ($c) {
  return function ($request, $response, $exception) use ($c) {
    $data;
    $data['status'] = array('code' => 666, 'desc' => 'Fatal error... Unknown Error ._.');
    return $c['response']->withJson($data, 200);
  };
};*/
///////COMENTAR ESTO PARA LANZARLO//////
$configuration = [
  'settings' => [
    'displayErrorDetails' => true,
  ],
];
$c = new Slim\Container($configuration);
/////////////////////////

$app = new Slim\App($c);
require_once APPPATH.'/Routes.php';

$app->run();
