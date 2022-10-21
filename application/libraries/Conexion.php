<?php  
  defined('BASEPATH') OR exit('No direct script access allowed');
  Class Conexion extends PDO{

  	//nombre base de datos
  	private $dbname;
  	//nombre servidor
  	private $host;
  	//nombre usuarios base de datos
  	private $user;
  	//password usuario
  	private $pass;
  	//puerto postgreSql
  	private $port;
   // variable tipo de clinica
    private $cli_tipo;
   
    protected $transactionCounter = 0;

    public $dbh;


  // Función para conectar a la base de datos
  //  public function __construct($dbname,$host,$user,$pass,$port,$cli_tipo){
  public function __construct(){
  }

  public function connect($data){
        try {

          $this->host   = $data['host'];
          $this->dbname = $data['dbname'];
          $this->user   = $data['user'];
          $this->pass   = $data['pass'];
          $this->port   = $data['port'];
          
          
          //$this->driver = $data['driver'];
          //$cli_tipo = 2;
          $this->dbh = new PDO("sqlsrv:Server=$this->host,$this->port;Database=$this->dbname", "$this->user", "$this->pass");
          $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          //Con el id de la clinica validamos el tipo de conexión 
         /* switch ($this->driver):
            case 'pgsql':
              $this->dbh = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->pass");       
                $this->dbh->exec("SET CHARACTER SET utf8");
            break;
            case 'sqlsrv':
              $this->dbh = new PDO("sqlsrv:Server=$this->host,$this->port;Database=$this->dbname", "$this->user", "$this->pass");
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            break;
            case 'mysql':
              $this->dbh = new PDO("mysql:host=$this->host;dbname=$this->dbname;$this->user;$this->pass");       
                $this->dbh->exec("SET CHARACTER SET utf8");
          endswitch;*/
        } catch(PDOException $e) {
          //print "¡Error!: " . $e->getMessage() . "<br/>";
          return false;
          die();
          
        }
        return true; 
  }
    
    //metodo que prepara el crud
    public function prepare($sql = [], $options = NULL){
      ini_set('max_execution_time', 0); 
      ini_set('memory_limit','-1');
      return $this->dbh->prepare($sql);      
    }  

  	//función para cerrar una conexión pdo
  	public function close_con(){
      $this->dbh = null; 
  	}

    //metodo para probar la conexion
    public function getDbHandler(){
      //return $this->dbh;
      if ($this->dbh):
         echo "Ingreso correctamente"; 
      else:
        echo "No Ingreso";
      endif;
    }

    //
    function all_get($table,$wheredt = array()){

      $consulta = "select * from {$table}";

        if(count($wheredt) >= 1){

            if(count($wheredt) == 1){
                $wh_k = $wheredt[0][0];
                $wh_v = $wheredt[0][1];
                $consulta .= "WHERE {$wh_k}={$wh_v}";

            }
        }

        $sql = $this->prepare($consulta);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);


    }

     /* public function getNamesColumns($query)
      {
            $total_column = $query->columnCount();
            for ($counter = 0; $counter < $total_column; $counter ++) {
                $meta = $query->getColumnMeta($counter);
                $column[] = $meta['name'];
            }
            return $column;
      }

      public function beginTransaction()
      {
          if (!$this->transactionCounter++) {
              return $this->dbh->beginTransaction();
          }
          $this->dbh->exec('SAVEPOINT trans'.$this->transactionCounter);
          return $this->transactionCounter >= 0;
      }

      public function commit()
      {
          if (!--$this->transactionCounter) {
              return $this->dbh->commit();
          }
          return $this->transactionCounter >= 0;
      }

      public function rollback()
      {
          if (--$this->transactionCounter) {
              $this->dbh->exec('ROLLBACK TO trans'.$this->transactionCounter + 1);
              return true;
          }
          return $this->dbh->rollback();
      }*/


  }
