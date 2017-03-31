<?php
  date_default_timezone_set('America/Los_Angeles');
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Cuisine.php";
  require_once __DIR__."/../src/Restaurant.php";

  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();

  $app['debug'] = true;

  $server = 'mysql:host=localhost;dbname=best_restaurants';
  $user = 'root';
  $pass = 'root';
  $db = new PDO($server, $user, $pass);

  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

  $app->get("/", function () use ($app) {
    return $app['twig']->render('index.html.twig', array('results'=>Cuisine::getAll()));
  });

  $app->post("/cuisine", function () use ($app) {
    if(!empty($_POST['cuisine'])){
      $new_cuisine = new Cuisine($_POST['cuisine']);
      $new_cuisine->save();
      return $app['twig']->render('index.html.twig', array('results'=>Cuisine::getAll()));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->get("/cuisine/{id}", function ($id) use ($app) {
    $cuisine = Cuisine::find($id);
    return $app['twig']->render('addrest.html.twig', array('cuisine'=>$cuisine));
  });

  $app->post("/addrest", function () use ($app) {
    if(!empty($_POST)){
      $new_rest = new Restaurant($_POST['name'], $_POST['location'], $_POST['cuisine_id']);
      $new_rest->save();
      $cuisine = Cuisine::find($new_rest->getCuisineId());
      return $app['twig']->render('rests.html.twig', array('rests'=>Restaurant::getRests($new_rest->getCuisineId()), 'cuisine'=>$cuisine));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->get("/rest/{id}", function ($id) use ($app) {
    $new_rest = Restaurant::find($id);
    return $app['twig']->render('rest.html.twig', array('rest'=>$new_rest));
  });

  $app->get("/rest/{id}/edit", function ($id) use ($app) {
    $new_rest = Restaurant::find($id);
    return $app['twig']->render('rest_edit.html.twig', array('rest'=>$new_rest));
  });

  $app->patch("/rest/{id}", function ($id) use ($app) {
    if(!empty($_POST)){
      $new_rest = Restaurant::find($id);
      $new_rest->update($_POST['name'],$_POST['location']);
      $cuisine = Cuisine::find($new_rest->getCuisineId());
      return $app['twig']->render('rests.html.twig', array('rests'=>Restaurant::getRests($new_rest->getCuisineId()), 'cuisine'=>$cuisine));
    } else {
      return $app['twig']->render('warning.html.twig');
    }
  });

  $app->delete("/rest/{id}", function ($id) use ($app) {
    $new_rest = Restaurant::find($id);
    $cuisine = Cuisine::find($new_rest->getCuisineId());
    $cuisine_id = $new_rest->getCuisineId();
    $new_rest->delete();
    return $app['twig']->render('rests.html.twig', array('rests'=>Restaurant::getRests($cuisine_id), 'cuisine'=>$cuisine));
  });

  return $app;
?>
