<?php
require('path.php');
if(!defined('MYSQL_LISTA_PDO_CLASS'))
{
  define('MYSQL_LISTA_PDO_CLASS', true);
  class MysqlListaPdo extends MysqlPdo
  {
    public function connect()
    {
      return $this->connect_pl('10.111.233.9', 'internet', 'b@zAd@nych', 'internet');
    }
  }
}
