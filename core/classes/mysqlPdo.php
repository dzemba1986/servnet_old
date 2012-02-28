<?php
if(! defined('MYSQL_PDO_CORE'))
{
  define('MYSQL_PDO_CORE', true);
  class MysqlPdo
  {
    public $pdo;
    public $result;
    public $num_rows;
    public function connect_pl($host, $user, $password, $database)
    {
      if(!$this->pdo)
      {
        try
        {
          $this->pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
          $this->pdo->exec("SET CHARACTER SET utf8");
        }
        catch(PDOException $e)
        {
          printf("Connect failed:". $e->getMessage() . "<br/>");
          exit();
        }
      }
      return $this->pdo;
    }
    public function connect()
    {
      if($pdo)
        return $this->pdo;
      else
      {
        printf("Theres no PDO object!");
        exit();
      }
    }
    //funkcja query zwraca:
    //false jeżeli wynik zapytania był pusty lub zapytanie nie zostało wykonane
    //true jeżeli zapytanie zostało wykonanee poprawnie i nie miało zwracać wartości
    //wartość, jeżeli wynik powinien być tylko jeden
    //tablicę wartości...
    public function query($query, $param)
    {
      $result_status = false;
      $stmt = null;
      try
      {
        $this->num_rows = 0;
        $pdo = $this->connect();
        $stmt = $pdo->prepare($query);
        if(is_array($param))
          foreach($param as $key=>$p)
          {
            $stmt->bindValue(':'.$key, $p);
          }
        $result_status = $stmt->execute();
      }
      catch(PDOException $e)
      {
        printf("Query failed: ". $e->getMessage() . "<br/>");
        exit();
      }
      if (!$result_status)
      {
        printf("Query failed: <br/>");
        var_dump($stmt->errorInfo());
        exit();
      }
      $this->result = $stmt->fetchAll();
      if (empty($this->result))
        return true;		//jezeli to bylo insert update delete drop lub wynik był pusty
      return $this->result;
    }
    public function query_insert($query, $param)
    {
      $result_status = false;
      $stmt = null;
      try
      {
        $this->num_rows = 0;
        $pdo = $this->connect();
        $stmt = $pdo->prepare($query);
        if(is_array($param))
          foreach($param as $key=>$p)
          {
            $stmt->bindValue(':'.$key, $p);
          }
        $result_status = $stmt->execute();
      }
      catch(PDOException $e)
      {
        printf("Query failed: ". $e->getMessage() . "<br/>");
        exit();
      }
      if (!$result_status)
      {
        printf("Query failed: <br/>");
        var_dump($stmt->errorInfo());
        exit();
      }
      return $pdo->lastInsertId();
    }
    public function query_obj($query, $param, $class, $args=null)
    {
      $result_status = false;
      $stmt = null;
      try
      {
        $this->num_rows = 0;
        $pdo = $this->connect();
        $stmt = $pdo->prepare($query);
        if(is_array($param))
          foreach($param as $key=>$p)
          {
            $stmt->bindValue(':'.$key, $p);
          }
        $result_status = $stmt->execute();
      }
      catch(PDOException $e)
      {
        printf("Query failed: ". $e->getMessage() . "<br/>");
        exit();
      }
      if (!$result_status)
      {
        printf("Query failed: <br/>");
        var_dump($stmt->errorInfo());
        exit();
      }
      $this->result = array();
      while($this->result[] =  $stmt->fetchObject($class, $args))
          {};
      if (empty($this->result))
        return true;		//jezeli to bylo insert update delete drop lub wynik był pusty
      return $this->result;
    }
    public function query_update($query, $param, $id, $tabela, $id_field)
    {
      $sql = $this->connect();
      $this->query_log($query, $id, $tabela, $id_field);
      return $this->query($query, $param);
    }
    protected function query_log($log_query, $id=null, $table=null, $id_field=null)
    {
      $id_collumn = null;
      $this->connect();
      $user = intval($_SESSION['user_id']);
      $ip = $_SERVER['REMOTE_ADDR'];
      $log_query = strtolower($log_query);
      if($id===null || !$table || !$id_field)
      {
        printf("Missing manadatory logging field! id=$id, table=$table, id_field=$id_field, query=$log_query<br/>");
        exit();
      }

      //getting current value
      try
      {

        $query = 'SELECT * FROM :table WHERE :id_field = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':table', $table, PDO::PARAM_STR);
        $stmt->bindValue(':id_field', $id_field, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $old = print_r($result[0]);

        //inserting log record

        $query = "INSERT INTO History (query_time, user_name, user_ip, query_text, old_value, object_id, table_name, id_field) VALUES(NOW(), :user, :ip, :log_query, :old, :id, :table, :id_field)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':user', $table, PDO::PARAM_STR);
        $stmt->bindValue(':ip', $table, PDO::PARAM_STR);
        $stmt->bindValue(':log_query', $table, PDO::PARAM_STR);
        $stmt->bindValue(':old', $table, PDO::PARAM_STR);
        $stmt->bindValue(':id', $table, PDO::PARAM_STR);
        $stmt->bindValue(':table', $id_field, PDO::PARAM_STR);
        $stmt->bindValue(':id_field', $id, PDO::PARAM_INT);
        $stmt->execute();
        //      echo $query;
      }
      catch(PDOException $e)
      {
        printf("Logging failed: ". $e->getMessage() . "<br/>");
        exit();
      }
    }
    public function begin()
    {
      $result_status = false;
      $stmt = null;
      try
      {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("BEGIN");
        $result_status = $stmt->execute();
        return $result_status;
      }
      catch(PDOException $e)
      {
        printf("Query failed: ". $e->getMessage() . "<br/>");
        exit();
      }
    }
    public function rollback()
    {
      $result_status = false;
      $stmt = null;
      try
      {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("ROLLBACK");
        $result_status = $stmt->execute();
        return $result_status;
      }
      catch(PDOException $e)
      {
        printf("Query failed: ". $e->getMessage() . "<br/>");
        exit();
      }
    }
    public function commit()
    {
      $result_status = false;
      $stmt = null;
      try
      {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("COMMIT");
        $result_status = $stmt->execute();
        return $result_status;
      }
      catch(PDOException $e)
      {
        printf("Query failed: ". $e->getMessage() . "<br/>");
        exit();
      }
    }
  }
}
