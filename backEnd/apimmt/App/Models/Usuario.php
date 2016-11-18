<?php
namespace App\Models;
defined ("APPPATH") OR die ("Acceso denegado :P");

use Core\Database;
use App\Models\Estados;
use Firebase\JWT\JWT;
class Usuario
{
  private $db;
  private $secret;
  //FUNCIONES PRINCIPALES
  function __construct()
  {
    $this->db = Database::instance();
    $this->secret = "spectrepro";
  }
  function add($data)
  {
    $fields = array('username', 'password', 'nombres', 'apellidos', 'correo', 'fnac');
    if ($this->validateData($data, $fields)) {
      if ($this->validateUsername($data['username'])) {
        $stmt = $this->db->prepare('insert into usuario values (null, ?, ?, ?, ?, ?, ?, 0)');
        $stmt->bindParam(1, $data['username']);
        $stmt->bindParam(2, $data['password']);
        $stmt->bindParam(3, $data['nombres']);
        $stmt->bindParam(4, $data['apellidos']);
        $stmt->bindParam(5, $data['correo']);
        $stmt->bindParam(6, $data['fnac']);
        $stmt->execute();
        $token = $this->generateToken($data['username'], $data['password']);
        $result = array('token' => $token, 'id' => $this->db->lastInsertId());
        return Estados::simpleRes('success', $result);
      } else {
        return Estados::simpleRes('warning', 'Ya existe un usuario registado como: '.$data['username']);
      }
    } else {
      return Estados::simpleRes('error', 'No se reconoce los campos requeridos (username,password,nombres,apellidos,correo,fnac)');
    }
  }
  function login($data)
  {
    $fields = array('username', 'password');
    if ($this->validateData($data, $fields)) {
      if($this->validateLogin($data['username'], $data['password'])){
        $token = $this->generateToken($data['username'], $data['password']);
        $result = array('token' => $token);
        return Estados::simpleRes('success', $result);
      } else {
        return Estados::simpleRes('warning', 'Username o password invalidos');
      }
    } else {
      return Estados::simpleRes('error', 'No se reconoce el campo \'username\' o \'password\'');
    }
  }
  function getProfile($data)
  {
    $fields = array('token');
    if ($this->validateData($data, $fields)) {
      if ($this->validateToken($data['token'])) {
        $user = $this->getUserByToken($data['token']);
        $user['record'] = $this->getUserPoints($user['id']);
        return $user;
      } else {
        return Estados::simpleRes('error', 'Algo paso con el token :(');
      }
    } else {
      return Estados::simpleRes('error', 'No se reconoce el campo token');
    }
  }
  function addTestimony($data)
  {
    $fields =  array('token', 'titulo', 'contenido', 'imei');
    if ($this->validateData($data, $fields)) {
      $logindata = (array)JWT::decode($data['token'], $this->secret, array('HS256'));
      if (isset($logindata['user'], $logindata['password'])) {
        if ($this->validateLogin($logindata['user'], $logindata['password'])) {
          $user = $this->getUserByUsername($logindata['user']);
          $sql = $this->db->prepare('insert into testimonio values (null, ?, ?, ?, ?)');
          $sql->bindParam(1, $user['id'], \PDO::PARAM_INT);
          $sql->bindParam(2, $data['titulo']);
          $sql->bindParam(3, $data['contenido']);
          $sql->bindParam(4, $data['imei']);
          $sql->execute();
          return Estados::simpleRes('success', 'Se adiciono el testimonio');
        } else {
          return Estados::simpleRes('error', 'Error con los datos de Login');
        }
      } else {
        return Estados::simpleRes('error', 'Error con el token');
      }
    } else {
      return Estados::simpleRes('error', 'No se pueden reconocer los campos token, titulo, contenido o imei');
    }
  }
  function newQuestion($data)
  {
    $fields = array('pregunta', 'correcta', 'inc1', 'inc2', 'inc3');
    if ($this->validateData($data, $fields)) {
      $stmt = $this->db->prepare('insert into pregunta values(null, ?, ?, ?, ?, ?)');
      $stmt->bindParam(1, $data['pregunta']);
      $stmt->bindParam(2, $data['correcta']);
      $stmt->bindParam(3, $data['inc1']);
      $stmt->bindParam(4, $data['inc2']);
      $stmt->bindParam(5, $data['inc3']);
      $result = $stmt->execute();
      if ($stmt) {
        return Estados::simpleRes('success', 'Nueva pregunta agregada correctamente');
      } else {
        return Estados::simpleRes('error', 'Algo salio mal :(');
      }
    } else {
      return Estados::simpleRes('error', 'no se reconocen los campo pregunta, correcta, inc1, inc2, inc3');
    }
  }
  //FUNCIONES COMPLEMENTO
  function getUserPoints($id)
  {
    $stmt = $this->db->prepare('select count(*) as total from respuesta where id_usuario = ?');
    $stmt->bindParam(1, $id, \PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    $total = $result['total'];

    $stmt = $this->db->prepare('select count(*) as correctas from respuesta where id_usuario = ? and correcto = 1');
    $stmt->bindParam(1, $id, \PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    $correctas = $result['correctas'];

    return array('total' => $total, 'correctas' => $correctas, 'incorrectas' => ($total-$correctas));
  }
  function getUserByToken($token)
  {
    $data = (array)JWT::decode($token, $this->secret, array('HS256'));
    return $this->getUserByUsername($data['user']);
  }
  function validateToken($token)
  {
    $data = (array)JWT::decode($token, $this->secret, array('HS256'));
    if (isset($data['user'], $data['password'])) {
      if ($this->validateLogin($data['user'], $data['password'])) {
        return true;
      }
      else {
        return false;
      }
    } else {
      return false;
    }
  }
  function getUserById($id)
  {
    $sql = $this->db->prepare('select * from usuario where id = ?');
    $sql->bindParam(1, $id, \PDO::PARAM_INT);
    $sql->execute();
    return $sql->fetch(\PDO::FETCH_ASSOC);
  }
  function getUserByUsername($username)
  {
    $sql = $this->db->prepare('select * from usuario where username = ?');
    $sql->execute(array($username));
    return $sql->fetch(\PDO::FETCH_ASSOC);
  }
  function validateLogin($username, $password)
  {
    $sql = $this->db->prepare('select count(*) as nro from usuario where username = ? and password = ?');
    $sql->execute(array($username, $password));
    $nro = $sql->fetch(\PDO::FETCH_ASSOC);
    if ($nro['nro'] == 1) {
      return true;
    } else {
      return false;
    }
  }
  //returns false if the $username exists in the database
  function validateUsername($username)
  {
    $sql = $this->db->prepare('select count(*) as nro from usuario where username = ?');
    $sql->bindParam(1, $username);
    $sql->execute();
    $nro = $sql->fetch(\PDO::FETCH_ASSOC);
    if ($nro['nro'] == 0) {
      return true;
    } else {
      return false;
    }
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
  function generateToken($user, $password)
  {
    $tokendata = array('user' => $user, 'password' => $password);
    $jwt = JWT::encode($tokendata, $this->secret);
    return $jwt;
  }
}
