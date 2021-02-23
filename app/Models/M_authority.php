<?php
/*
*
* Model для работы с данными контр. орган
*
*/
namespace App\Models;

use CodeIgniter\Model;

class M_authority extends Model
{
  protected $table = 'authority';
  protected $primaryKey = 'Id_au';
  protected $allowedFields = ['TitleA'];
  /* Получение информации об контр. органе */
  public function getAuInfo()
  {
    return $this->db->table('authority')
    ->get()->getResultArray();
  }
  /* Получение 1 записи об контр. органе */
  public function getAuOnly($id)
  {
    return $this->asArray()
    ->where('Id_au',$id)
    ->first();
  }
  public function auNameToId($value)
  {
    return $this->db->table('authority')
    ->select('Id_au')
    ->where('TitleA', $value)
    ->limit(1)
    ->get()->getRow();
  }

}
