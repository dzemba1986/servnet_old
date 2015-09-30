<?php
if(!defined('MYSQL_SEU_CLASS'))
{
  define('MYSQL_SEU_CLASS', true);
  class MysqlSeu extends MysqlMain
  {
    public function connect()
    {
      return $this->connect_pl('10.111.233.9', 'admin', 'b@zAd@nych', 'siec');
    }
  }
}
