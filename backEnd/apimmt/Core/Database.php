<?php
namespace Core;
defined("APPPATH") OR die("Access denied");

class Database
{
	// nombre del usuario de la base de datos
	private $_dbUser;
	//password de la base de datos
	private $_dbPassword;
	// @desc nombre del host
	private $_dbHost;
	// @desc nombre de la base de datos
	protected $_dbName;
	// @desc conexión a la base de datos
	private $_connection;
    // instancia de la base de datos
    private static $_instance;

	/**
	 * [__construct]
	 */
    public function __construct()
    {
       try {
		   //load from config/config.ini
		   $config = $this->getConfig();
		   $this->_dbHost = $config["host"];
		   $this->_dbUser = $config["user"];
		   $this->_dbPassword = $config["password"];
		   $this->_dbName = $config["database"];

           $this->_connection = new \PDO('mysql:host='.$this->_dbHost.'; dbname='.$this->_dbName, $this->_dbUser, $this->_dbPassword);
           $this->_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
           $this->_connection->exec("SET CHARACTER SET utf8");
       }
       catch (\PDOException $e)
       {
           print "Error!: " . $e->getMessage();
           die();
       }
    }
		public function lastInsertId(){
			return $this->_connection->lastInsertId();
		}
  /**

  */
  public static function getConfig()
  {
      return parse_ini_file(PROJECTPATH . '/include/config.ini');
  }
	/**
	 * [prepare]
	 * @param  [type] $sql [description]
	 * @return [type]      [description]
	 */
	public function prepare($sql)
    {
        return $this->_connection->prepare($sql);
    }

	/**
	 * [instance singleton]
	 * @return [object] [class database]
	 */
    public static function instance()
    {
        if (!isset(self::$_instance))
        {
            $class = __CLASS__;
            self::$_instance = new $class;
        }
        return self::$_instance;
    }

    /**
     * [__clone Evita que el objeto se pueda clonar]
     * @return [type] [message]
     */
    public function __clone()
    {
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
    }
}
