<?php
if(!defined('PAGING_MENU'))
  die('Nieuprawnione wywolanie!');
$variables = "order=$order&amp;tryb2=$tryb2&amp;tryb=$tryb&amp;od=$od&amp;do=$do&amp;payment=$payment&amp;activation=$activation&amp;";
if($paging->getPages() > 1): 
if($paging->getPageNum() > 1):
?>
<a class="paging_menu" href="<?php echo $site."?".$variables."page_number=1&amp;rows_per_page=".$paging->getRowsPerPage();?>" > &lt;&lt; </a>
<a class="paging_menu" href="<?php echo $site."?".$variables."page_number=".($paging->getPageNum() - 1)."&amp;rows_per_page=".$paging->getRowsPerPage();?>"> &lt; </a> 
<?php endif; ?>
<?php for($page=1; $page < $paging->getPages()+1; $page++)
  if($page!=$paging->getPageNum() && abs($page - $paging->getPageNum()) <= 4): ?>
    <a  class="paging_menu" href="<?php echo $site."?".$variables."page_number=".$page."&amp;rows_per_page=".$paging->getRowsPerPage()."\">$page</a>";
  elseif($page==$paging->getPageNum()):
    echo "<font class=\"paging_active\"> $page </font>";
  elseif($page!=$paging->getPageNum() && abs($page - $paging->getPageNum()) == 5): 
    echo "<font class=\"paging_dots\"> ... </font>";
  endif;
if($paging->getPageNum() < $paging->getPages()): ?>
<a class="paging_menu" href="<?php echo $site."?".$variables."page_number=".($paging->getPageNum() + 1)."&amp;rows_per_page=".$paging->getRowsPerPage();?>" > &gt; </a>
<a class="paging_menu" href="<?php echo $site."?".$variables."page_number=".($paging->getPages())."&amp;rows_per_page=".$paging->getRowsPerPage();?>"> &gt;&gt; </a>
<?php endif; endif;?>

