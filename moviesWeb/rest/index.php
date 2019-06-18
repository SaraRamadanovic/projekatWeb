<?php
require '../vendor/autoload.php';

Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=movies','root',''));

Flight::route('/', function(){
    echo 'hello sara!';
});



Flight::route('GET /movies', function(){
    $movies = Flight::db()->query('SELECT * FROM movie', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($movies);
});

Flight::route('POST /movies', function(){
    $request = Flight::request()->data->getData();
    if (isset($request['id']) && $request['id'] !=''){
      $update = "UPDATE movie SET category= :category, title = :title, year = :year WHERE id=:id";
      $stmt= Flight::db()->prepare($update);
      $stmt->execute($request);
      Flight::json(['message' => "Movie ".$request['title']." has been updated successfully"]);
    }else{
      unset($request['id']);
      $insert = "INSERT INTO movie (category, title, year) VALUES(:category, :title, :year)";
      $stmt= Flight::db()->prepare($insert);
      $stmt->execute($request);
      Flight::json(['message' => "Movie ".$request['title']." has been added successfully"]);
    }
});

Flight::route('DELETE /movies/@id', function($id){
  $query = "DELETE FROM movie WHERE id = :id";
  $stmt= Flight::db()->prepare($query);
  $stmt->execute(['id' => $id]);
  Flight::json(['message' => "Movie has been deleted successfully"]);
});







Flight::start();




?>
