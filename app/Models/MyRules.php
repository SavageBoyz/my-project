<?php
/*
*
* Custom rules
*
*/
namespace App\Models;

use CodeIgniter\Model;

class MyRules extends Model
{
  protected $table = 'inspectors';
  protected $primaryKey = 'Id_ins';
  protected $allowedFields = ['AuthorityId', 'SbeId' ,'DateFr', 'DateTo', 'Duration'];
  /*Поиск дублей*/
  public function is_unique_all(string $str, string $str2): bool
    {
      if($str2 == -1){
        return 1;
      }
      return 0;
    }
    /*********************/
    /* Сравнение дат*/
    public function timestring_less_than($str,$str2): bool
    {
      $date_timestamp_1 = strtotime($str);
      $date_timestamp_2 = strtotime($str2);
      if($str > $str2){
        return 0;
      }
      return 1;
    }
    /**********************/
}
