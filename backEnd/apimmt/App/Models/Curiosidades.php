<?php
namespace App\Models;

use Core\Database;
use App\Models\Estados;
class Curiosidades
{
 private $db;
 private $secret;
 //FUNCIONES PRINCIPALES
 function __construct()
 {
   $this->db = Database::instance();
   $this->secret = "spectrepro";
 }
 function add($url, $desc)
 {
   $stmt = $this->db->prepare('insert into curiosidad values(null, ?, ?)');
   $stmt->bindParam(1, $desc);
   $stmt->bindParam(2, $url);
   $stmt->execute();
 }
 function getRandomImage()
 {
   $stmt = $this->db->prepare('select * from curiosidad order by rand() limit 1');
   $stmt->execute();
   $result = $stmt->fetch(\PDO::FETCH_ASSOC);
   return Estados::simpleRes("success", $result);
 }
 function getRandomQuestion()
 {
   $stmt = $this->db->prepare('select * from pregunta order by rand() limit 1');
   $stmt->execute();
   $result = $stmt->fetch(\PDO::FETCH_ASSOC);
   return Estados::simpleRes("success", $result);
 }
}

?>
