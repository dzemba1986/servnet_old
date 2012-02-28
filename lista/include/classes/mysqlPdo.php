<?php

if(!defined('MYSQL_LISTA_PDO_CLASS'))
{
  define('MYSQL_LISTA_PDO_CLASS', true);
  class MysqlListaPdo extends MysqlPdo
  {
    public function connect()
    {
      return $this->connect_pl('localhost', 'internet', 'szczurek20P4', 'internet');
    }
  }
}
