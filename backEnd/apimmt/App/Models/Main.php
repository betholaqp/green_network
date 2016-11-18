<?php
namespace App\Models;

defined("APPPATH") or die("Usted no deberia ver esto xD");

use Core\Database;
use App\Models\Estados;
class Main
{
  private $db;
  //FUNCIONES PRINCIPALES
  function __construct()
  {
    $this->db = Database::instance();
  }
  function getAllModules()
  {
    $result = $this->getAll();
    return Estados::simpleRes("success", $result);
  }
  function getNearModules($data)
  {
    $fields = array("latitud", "longitud");
    if ($this->validateData($data, $fields)) {
      $dist = array();
      $result = $this->getAll();
      foreach ($result as $key => &$value) {
        $value['dis'] = $this->getDistance($data['latitud'], $data['longitud'], $value['lat'], $value['lon']);
        array_push($dist, $value['dis']);
      }
      array_multisort($dist, $result);
      //var_dump($dist);
      //var_dump($result);
      return Estados::simpleRes("success", $result);
    } else {
      return Estados::simpleRes('error', 'No se reconoce el campo \'latitud\' o \'longitud\'');
    }
  }
  function addModule($data)
  {
    $fields = array('id', 'lat', 'lon', 'nom', 'dir', 'des', 'tel', 'tipo');
    if ($this->validateData($data, $fields)) {
      $sql = $this->db->prepare('insert into modulo values (null, ?, ?, ?, ?, ?, ?, ?)');
      $sql->bindParam(1, $data['lat']);
      $sql->bindParam(2, $data['lon']);
      $sql->bindParam(3, $data['nom']);
      $sql->bindParam(4, $data['dir']);
      $sql->bindParam(5, $data['des']);
      $sql->bindParam(6, $data['tel']);
      $sql->bindParam(7, $data['tipo']);
      $sql->execute();
      return Estados::simpleRes('success');
    } else {
      return Estados::simpleRes('error', 'no se reconocen los campos');
    }
  }
  function deleteModule($id)
  {
    $sql = $this->db->prepare('delete from modulo where id = ?');
    $sql->bindParam(1, $id, \PDO::PARAM_INT);
    $sql->execute();
    return Estados::simpleRes('success', 'Se eliminio correctamente');
  }
  function deleteTest($id)
  {
    $sql = $this->db->prepare('delete from testimonio where id = ?');
    $sql->bindParam(1, $id, \PDO::PARAM_INT);
    $sql->execute();
    return Estados::simpleRes('success', 'Se eliminio correctamente');
  }
  //FUNCIONES COMPLEMENTO
  function getAll()
  {
    $sql = $this->db->prepare('select * from modulo');
    $sql->execute();
    $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
  }
  function getDistance($lat1, $long1, $lat2, $long2)
  {
    $earth = 6371; //km change accordingly
    //$earth = 3960; //miles
    //Point 1 cords
    $lat1 = deg2rad($lat1);
    $long1= deg2rad($long1);
    //Point 2 cords
    $lat2 = deg2rad($lat2);
    $long2= deg2rad($long2);
    //Haversine Formula
    $dlong=$long2-$long1;
    $dlat=$lat2-$lat1;
    $sinlat=sin($dlat/2);
    $sinlong=sin($dlong/2);
    $a=($sinlat*$sinlat)+cos($lat1)*cos($lat2)*($sinlong*$sinlong);
    $c=2*asin(min(1,sqrt($a)));
    $d=$earth*$c;
    return $d;
  }
  function validateData($data, $fields)
  {
    foreach ($fields as $value) {
      if (! isset($data[$value])) {
        return false;
      }
    }
    return true;
  }
}

?>
