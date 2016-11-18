<?php
namespace App\Models;
/**
 *
 */
class Estados
{
  private $data = array('status' => null, 'content' => null, 'header' => null);
  public static function getError($code, $mensaje)
  {
    $data['status'] = array('code' => $code, 'desc' => $mensaje);
    return $data;
  }
  public static function getSuccess($content, $mensaje)
  {
    $data['status'] = array ('code' => 200, 'desc' => $mensaje);
    $data['content'] = $content;
    return $data;
  }
  public static function getCompletSuccess($header, $content, $mensaje)
  {
    $data['status'] = array ('code' => 200, 'desc' => $mensaje);
    $data['header'] = $header;
    $data['content'] = $content;
    return $data;
  }
  public static function simpleRes($status, $content)
  {
    return array("status"=>$status, "content"=>$content);
  }
}

?>
