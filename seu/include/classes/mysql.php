<?php
class MysqlSeu extends MysqlMain
{
  public function connect()
  {
    return $this->connect_pl('localhost', 'admin', 'WyGn2jEw0', 'siec');
  }
}
