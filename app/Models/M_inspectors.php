<?php 
/*
*
* Model для работы с данными Inspectors
*
*/
namespace App\Models;

use CodeIgniter\Model;

class M_inspectors extends Model
{
	  protected $table = 'inspectors';
  	protected $primaryKey = 'Id_ins';
  	protected $allowedFields = ['AuthorityId', 'SbeId' ,'DateFr', 'DateTo', 'Duration'];

    protected $column_order = array('Id_ins','TitleS','TitleA','DateFr','DateTo', 'Duration');
    protected $order = array('Id_ins' => 'asc');
  /* Получение информации об Inspectors */
  public function getInspetInfo()
  {
    return $this->db->table('inspectors')
    ->join('sbe','sbe.Id_sbe = inspectors.SbeId')
    ->join('authority','authority.Id_au = inspectors.AuthorityId')
    ->get()->getResultArray();
  }
  /* Получение информации об 1 записи Inspectors */
  public function getInspetOnly($id)
  {
    return $this->asArray()
    ->where('Id_ins',$id)
    ->join('sbe','sbe.Id_sbe = inspectors.SbeId')
    ->join('authority','authority.Id_au = inspectors.AuthorityId')
    ->first();
  }
  /**************************************/
  /* Валидация данных импортированных из Excel */
  public function validationExcel(array $data)
  {
    /* Проверка на null,0,'' */
    foreach ($data as $key => $value) {
      if(empty($value)){
        return 0;
      }
    }
    /* Проверка дат*/
    $date_timestamp_1 = strtotime($data['DateFr']);
    $date_timestamp_2 = strtotime($data['DateTo']);

    if($date_timestamp_2 < $date_timestamp_1){
      return 0;
    }
    if( (!is_numeric($data['Duration'])) or (100 < $data['Duration']) or ( 0 >= $data['Duration'])){
      return 0;
    }
    /* Проверка на уникальность*/
    $row = $this->db->table('inspectors')
              ->select('Id_ins')
              ->where('SbeId',$data['SbeId'])
              ->where('AuthorityId',$data['AuthorityId'])
              ->where('DateFr',date("Y-m-d", strtotime($data['DateFr'])))
              ->where('DateTo',date("Y-m-d", strtotime($data['DateTo'])))
              ->where('Duration',$data['Duration'])
              ->limit(1)
              ->get()->getRow();

      if(empty($row)){
        return 1;
      }
      else {
        return 0;
      }
    }
   /*****************************/

   /*Вывод таблицы*/
  public function get_datatables($idF, $sbe_nameF, $au_nameF, $date_frF, $date_toF, $durationF,$order_value,$length,$start)
  {
    /***ALL_FILTERS***/

    /*Фильтр по ID*/
      if(empty($idF)){
        $condition_idF = "";
      }else{
        $condition_idF = "AND Id_ins = '$idF'";
      }
    /*****/

    /*Фильтр по названию СМП*/
    if(empty($sbe_nameF)){
      $condition_sbe_nameF = "";
    }else{
      $condition_sbe_nameF = "AND TitleS LIKE '%$sbe_nameF%'";
    }
    /*****/

    /*Фильтр по названию контр. органа*/
    if(empty($au_nameF)){
      $condition_au_nameF = "";
    }else{
      $condition_au_nameF = "AND TitleA LIKE '%$au_nameF%'";
    }
    /*****/   

    /*Фильтр по дате от*/
    if(empty($date_frF)){
      $condition_date_frF = "";
    }else{
      $condition_date_frF = "AND DateFr = '$date_frF'";
    }
    /*****/   

    /*Фильтр по дате до*/
    if(empty($date_toF)){
      $condition_date_toF = "";
    }else{
      $condition_date_toF = "AND DateTo = '$date_toF'";
    }
    /*****/ 

    /*Фильтр по длительности*/
    if(empty($durationF)){
      $condition_durationF = "";
    }else{
      $condition_durationF = "AND Duration = '$durationF'";
    }
    /*****/ 

    /****************/  

    /* Получение запроса для поиска */
      $condition_search = "Id_ins != '' $condition_idF $condition_sbe_nameF $condition_au_nameF $condition_date_frF $condition_date_toF $condition_durationF";
    /********************************/  
    /*Order*/
    if($order_value){
      $result_order = $this->column_order[$order_value['0']['column']];
      $result_dir = $order_value['0']['dir'];
    }else if($this->order){
      $order = $this->order;
      $result_order = key($order);
      $result_dir = $order[key($order)];
    }
    /*******/

    /*Input table*/
    if($length!=-1);
      $builder = $this->db->table('inspectors')
      ->join('sbe','sbe.Id_sbe = inspectors.SbeId')
      ->join('authority','authority.Id_au = inspectors.AuthorityId');
      $query = $builder->select("*")
                       ->where($condition_search)
                       ->orderBy($result_order,$result_dir)
                       ->limit($length,$start)
                       ->get();
      return $query->getResult();
    /************/
  }

  /*Все записи*/
  public function Count_all()
  {
    $sQuery = "SELECT COUNT(Id_ins) as countData FROM inspectors";
    $db = db_connect();
    $query = $db->query($sQuery)->getRow();
    return $query;
  }
  /***********/
  public function Count_filter($idF, $sbe_nameF, $au_nameF, $date_frF, $date_toF, $durationF)
  {
      /***ALL_FILTERS***/

      /*Фильтр по ID*/
      if(empty($idF)){
        $condition_idF = "";
      }else{
        $condition_idF = "AND Id_ins = '$idF'";
      }
    /*****/

    /*Фильтр по названию СМП*/
    if(empty($sbe_nameF)){
      $condition_sbe_nameF = "";
    }else{
      $condition_sbe_nameF = "AND TitleS LIKE '%$sbe_nameF%'";
    }
    /*****/

    /*Фильтр по названию контр. органа*/
    if(empty($au_nameF)){
      $condition_au_nameF = "";
    }else{
      $condition_au_nameF = "AND TitleA LIKE '%$au_nameF%'";
    }
    /*****/   

    /*Фильтр по дате от*/
    if(empty($date_frF)){
      $condition_date_frF = "";
    }else{
      $condition_date_frF = "AND DateFr = '$date_frF'";
    }
    /*****/   

    /*Фильтр по дате до*/
    if(empty($date_toF)){
      $condition_date_toF = "";
    }else{
      $condition_date_toF = "AND DateTo = '$date_toF'";
    }
    /*****/ 

    /*Фильтр по длительности*/
    if(empty($durationF)){
      $condition_durationF = "";
    }else{
      $condition_durationF = "AND DateTo = '$durationF'";
    }
    /*****/ 

    /****************/  

    /* Получение запроса для поиска */  
    $condition_search = "$condition_idF $condition_sbe_nameF $condition_au_nameF $condition_date_frF $condition_date_toF $condition_durationF";
    /********************************/ 

    $sQuery = "SELECT COUNT(Id_ins) as countData FROM inspectors WHERE Id_ins != '' $condition_search";
    $db = db_connect();
    $query = $db->query($sQuery)->getRow();
    return $query;
  }
}

