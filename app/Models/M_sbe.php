<?php 
/*
*
* Model для работы с данными СМП
*
*/
namespace App\Models;

use CodeIgniter\Model;

class M_sbe extends Model
{
  protected $table = 'sbe';
  protected $primaryKey = 'Id_sbe';
  protected $allowedFields = ['TitleS'];
  
  /* Получение информации об CMП */
  public function getSbeInfo()
  {
    return $this->db->table('sbe')
    ->get()->getResultArray();
  }
  /* Получение информации об 1 записе CMП */
  public function getSbeOnly($id)
  {
    return $this->asArray()
    ->where('Id_sbe',$id)
    ->first();
  }
  public function sbeNameToId($value)
  {
    return $this->db->table('sbe')
    ->select('Id_sbe')
    ->where('TitleS', $value)
    ->limit(1)
    ->get()->getRow();
  }
}

