<?php
if(!defined('MYSQL_SEU_PDO_CLASS'))
{
  define('MYSQL_SEU_PDO_CLASS', true);
  class MysqlSeuPdo extends MysqlPdo
  {
    public function connect()
    {
      return $this->connect_pl('10.111.233.9', 'admin', 'b@zAd@nych', 'siec');
    }
  }
}
