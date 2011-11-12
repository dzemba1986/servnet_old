<?php
if(!defined('MYSQL_SEU_CLASS'))
{
  define('MYSQL_SEU_CLASS', true);
  class MysqlSeu extends MysqlMain
  {
    public function connect()
    {
      return $this->connect_pl('localhost', 'admin', 'WyGn2jEw0', 'siec');
    }
  }
}
