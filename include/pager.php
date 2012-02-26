<?php
/*
 *	程式名稱 	: pager(分頁器)		
 *	使用環境	: PHP + MySQL
 *	作者		: sdcomputer(鬢角)@nttu
 *	最後更新	: 2010.9.11(By Martin)
 *	使用說明	: 
 *	請在使用本類別之前先進行好對資料庫的連線，本類別將會利用session記錄某些資料。不適合者勿用。
 *	使用本類別需具備基礎class相關使用知識。
 *	並不含任何資訊安全上的防護，若要改用本範例檔請自行加強防護。
 */

class pager {
  private $statement = "";    //sql指令
  private $num_rows = 0;      //一頁幾筆
  private $total_rows = 0;    //總資料筆數
  private $page_now = 1;          //現在在第幾頁
  private $link_per_page;     //一頁要顯示多少個連結

  //設定SQL
  public function set_statement($stmt){						
    $this->statement = $stmt;
    $result = mysql_query($this->statement)or die(mysql_error());
    $this->total_rows = mysql_num_rows($result);
  }

  //設定一頁要呈現幾筆資料
  public function set_num_rows($nr){
    $this->num_rows = $nr;
  }

  //設定一頁要有幾筆XX頁
  public function set_link_per_page($num){
    $this->link_per_page = $num;
  }
  //opration function 
  public function get_page($num){								
    //列出第幾頁的資料,回傳值為mysql_query指令的返回值
    $num = ($this->has_page($num)) ? $num : 1;
    $this->page_now = $num;
    $page_start = ($num-1) * $this->num_rows;
    $sql = $this->statement." LIMIT $page_start , ".$this->num_rows;
    $result = mysql_query($sql)or die(mysql_error());
    return $result;
  }

  //回傳是否存在第$num頁
  public function has_page($num){								
    $num = ($num-1) * $this->num_rows;
    if($num>$this->total_rows || $num<1)
      return false;
    else
      return true;
  }

  //回傳總共有幾頁
  public function how_many_pages(){							
    return ceil($this->total_rows/$this->num_rows);
  }

  //回傳資訊和連結的字串
  public function get_links($link){
    $strLinks = "";
    if($this->how_many_pages() == 1 ){
      $strLinks = "總共".$this->how_many_pages()."頁，現為第".$this->page_now."頁";
      return $strLinks; 
    }
    if($this->page_now > 1){
      $strLinks .=  "<a href=$link?page=1>首頁&nbsp;</a>";
      $strLinks .= "<a href=$link?page=".($this->page_now - 1).">上一頁&nbsp;</a>";
    }
    $start = $this->page_now;
    if($this->how_many_pages() - $start + 1 < $this->link_per_page){
      $start = $start - $this->link_per_page + 1 - $start + $this->how_many_pages();
      while($start<1)$start++;
    }
    for($i=$start ; $i<=$this->how_many_pages() && $i-$start<$this->link_per_page;$i++){
      if($i==$this->page_now)
        $strLinks .= "&nbsp;$i&nbsp;";
      else
        $strLinks .= "<a href=$link?page=$i>&nbsp;$i&nbsp;</a>";
    }
    if($this->page_now != $this->how_many_pages()){
      $strLinks .= "<a href=$link?page=".($this->page_now + 1).">&nbsp;下一頁&nbsp;</a>"; 
      $strLinks .= "<a href=$link?page=".($this->how_many_pages()).">末頁&nbsp;</a>";
    }
    $strLinks .= "&nbsp;總共".$this->how_many_pages()."頁，現為第".$this->page_now."頁";
    return $strLinks;
  }
}

?>
