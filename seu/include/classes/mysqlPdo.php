<?php
if(!defined('MYSQL_SEU_PDO_CLASS'))
{
  define('MYSQL_SEU_PDO_CLASS', true);
  class MysqlSeuPdo extends MysqlPdo
  {
    public function connect()
    {
      return $this->connect_pl('localhost', 'admin', 'WyGn2jEw0', 'siec');
    }
  }
}
