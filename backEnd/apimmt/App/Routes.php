<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Usuario as Usuario;
use App\Models\Curiosidades as Curiosidades;
// use App\Models\Noticia as Noticia;
// use App\Models\Place as Place;
use App\Models\Main as Main;

$app->get('/', function(){
  echo "Welcome to the API from MMT event :D";
});
//ADMIN
$app->post('/uploadquestion', function (Request $request, Response $response) {
  $user = new Usuario();
  $result = $user->newQuestion($request->getParsedBody());
  return ($response->withJson($result));
});
$app->post('/upload', function (Request $request, Response $response) {
  $files = $request->getUploadedFiles();
  $data = $request->getParsedBody();
  $image = $files['newfile'];
  $desc = $data['desc'];
  $mediaType = $image->getClientMediaType();
  $ext = '.'.substr($mediaType, 6);
  $name = preg_replace('[\s+]','', $image->getClientFilename());
  $path = PROJECTPATH.'/modules/curiosidades/'.$name;
  $image->moveTo($path);
  $url = IP.'/apimmt/modules/curiosidades/'.$name;
  $c = new curiosidades();
  $c->add($url, $data['desc']);
});
//PUBLIC
$app->get('/randomquestion', function (Request $request, Response $response) {
  $c = new Curiosidades();
  $result = $c->getRandomQuestion();
  return ($response->withJson($result));
});
$app->post('/user', function (Request $request,Response $response) {
  $user = new Usuario();
  $result = $user->add($request->getParsedBody());
  return ($response->withJson($result));
});
$app->get('/random', function (Request $request, Response $response)
{
  $c = new Curiosidades();
  $result = $c->getRandomImage();
  return ($response->withJson($result));
});
$app->post('/login', function (Request $request, Response $response) {
  $user = new Usuario();
  $result = $user->login($request->getParsedBody());
  return ($response->withJson($result));
});
$app->post('/profile', function (Request $request, Response $response) {
  $user = new Usuario();
  $result = $user->getProfile($request->getParsedBody());
  return ($response->withJson($result));
});

$app->post('/modulo', function (Request $request, Response $response) {
  $main = new Main();
  $result = $main->addModule($request->getParsedBody());
  return ($response->withJson($result));
});
$app->delete('/modulo/{id:[0-9]+}', function (Request $request, Response $response, $args) {
  $main = new Main();
  $result = $main->deleteModule($args['id']);
  return ($response->withJson($result));
});
$app->get('/modulos', function(Request $request, Response $response) {
  $main = new Main();
  $result = $main->getAllModules();
  return ($response->withJson($result));
});
$app->post('/modulos', function(Request $request, Response $response) {
  $main = new Main();
  $result = $main->getNearModules($request->getParsedBody());
  //var_dump($request->getParsedBody());
  return ($response->withJson($result));
});
$app->delete('/testimonio/{id:[0-9]+}', function (Request $request, Response $response, $args) {
  $main = new Main();
  $result = $main->deleteTest($args['id']);
  return ($response->withJson($result));
});
$app->post('/testimonio', function (Request $request, Response $response)
{
  $user = new Usuario();
  $result = $user->addTestimony($request->getParsedBody());
  return ($response->withJson($result));
});
$app->get('/testimonios', function (Request $request, Response $response)
{
  $user = new Usuario();
  $result = $user->getTestimonials();
  return ($response->withJson($result));
});
$app->get('/prueba', function (Request $request,Response $response)
{
  $user = new Usuario();
  return $response->withJson($user->getUserById(1));
})
?>
