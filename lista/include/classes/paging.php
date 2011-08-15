<?php
Class Paging
{
  private $total_rows;
  private $page_number;
  private $rows_per_page;
  public function setTotalRows($rows)
  {
    $this->total_rows = intval($rows);
    if($this->page_number > $this->getPages())
      $this->page_number = $this->getPages();
  }
  public function setPageNum($page)
  {
    $this->page_number = abs(intval($page));
  }
  public function setRowsPerPage($rows)
  {
    $this->rows_per_page = abs(intval($rows));
    if($_SESSION['remember_paging']==true)
      $_SESSION['rows_per_page'] = abs(intval($rows));
  }
  public function initialize($page, $rows_per_page)
  {
    if($page >1 )
      $this->setPageNum($page);
    else
      $this->setPageNum(1);
    if($rows_per_page >=1 )
      $this->setRowsPerPage($rows_per_page);
    else
      $this->setRowsPerPage($_SESSION['rows_per_page']);
  }
  public function getPageNum()
  {
    return $this->page_number;
  }
  public function getRowsPerPage()
  {
    return $this->rows_per_page;
  }
  public function getOffset()
  {
    return (($this->page_number - 1)* $this->rows_per_page);
  }
  public function getPages()
  {
    if($this->total_rows && $this->rows_per_page)
      return ceil($this->total_rows/$this->rows_per_page);
    return 1;
  }
}

