<?php
/** 
 * Description:
 * This class is a simple pager used in table query results.
 * @author Martin Ku
 * @package class
 */

class pager {
  private $statement = "";    //SQL敘述
  private $num_rows = 0;      //一頁幾筆
  private $total_rows = 0;    //總資料筆數
  private $page_now = 1;      //現在在第幾頁
  private $link_per_page;     //一頁要顯示多少個連結

  /**
   * Description: 
   * 設定SQL敘述; 撈出對應result; 計算總筆數
   * @param string $stmt SQL statement
   * @return NULL
   */
  public function set_statement($stmt){						
    $this->statement = $stmt;
    $result = mysql_query($this->statement)or die(mysql_error());
    $this->total_rows = mysql_num_rows($result);
  }

  /**
   * Description: 
   * 設定一頁顯示幾筆資料
   * @param int $nr number of rows per page
   * @return NULL
   */
  public function set_num_rows($nr){
    $this->num_rows = $nr;
  }

  /**
   * Description: 
   * 設定每頁footer有幾個[XX]頁
   * @param int $nr number of links per page
   * @return NULL
   */
  public function set_link_per_page($num){
    $this->link_per_page = $num;
  }

  /**
   * Description: 
   * 取得指定頁的result; 該頁不存在的話回傳第一頁
   * @param int $num result of the specific page
   * @return result set return by mysql_query()
   */
  public function get_page($num){								
    //列出第幾頁的資料,回傳值為mysql_query指令的返回值
    $num = ($this->has_page($num)) ? $num : 1;
    $this->page_now = $num;
    $page_start = ($num-1) * $this->num_rows;
    $sql = $this->statement." LIMIT $page_start , ".$this->num_rows;
    $result = mysql_query($sql)or die(mysql_error());
    return $result;
  }

  /**
   * Description: 
   * 檢查指定頁是否存在
   * @param int $num a number of page
   * @return bool
   */
  public function has_page($num){								
    $num = ($num-1) * $this->num_rows;
    return !($num>$this->total_rows || $num<1);
  }

  /**
   * Description: 
   * 回傳總共幾頁
   * @return int total number of page
   */
  public function how_many_pages(){							
    return ceil($this->total_rows/$this->num_rows);
  }

  /**
   * Description: 
   * 回傳資訊和連結的字串
   * @param string $link the link of the page(e.g queryResult.php)
   * @return string 顯示分頁資訊和連結的一行footer
   */
  public function get_links($link){
    $strLinks = "";
    $cssClass = 'Button';
    $buttonText = '&nbsp;&nbsp;';

    //如果總共只有1頁，不顯示任何連結
    if($this->how_many_pages() == 1 ){
      //$strLinks = "共1頁";
      return $strLinks; 
    }
    if($this->page_now > 1){
      $strLinks .= "<a title='回第一頁' class='$cssClass first' href=$link?page=1>$buttonText</a>";
      $strLinks .= "<a title='上一頁' class='$cssClass prev' href=$link?page=".($this->page_now - 1).">$buttonText</a>";
    }
    $start = $this->page_now;
    if($this->how_many_pages() - $start + 1 < $this->link_per_page){
      $start = $start - $this->link_per_page + 1 - $start + $this->how_many_pages();
      while($start<1)$start++;
    }
    for($i=$start ; $i<=$this->how_many_pages() && $i-$start<$this->link_per_page;$i++){
      if($i==$this->page_now){ $strLinks .= "<a class='$cssClass ui-highlight' href=$link?page=$i>$i</a>"; }
      else{ $strLinks .= "<a class='$cssClass' href=$link?page=$i>$i</a>"; }
    }
    if($this->page_now < $this->how_many_pages()){
      $strLinks .= "<a title='下一頁' class='$cssClass next' href=$link?page=".($this->page_now + 1).">$buttonText</a>"; 
      $strLinks .= "<a title='最後一頁' class='$cssClass last' href=$link?page=".($this->how_many_pages()).">$buttonText</a>";
    }
    $strLinks .= "&nbsp;共".$this->how_many_pages()."頁";
    return $strLinks;
  }
}

?>
