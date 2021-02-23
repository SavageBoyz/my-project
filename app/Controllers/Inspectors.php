<?php
/*
*
* Controller для работы с данными Inspectors
*
*/
namespace App\Controllers;

use App\Models\M_inspectors;
use App\Models\M_authority;
use App\Models\M_sbe;
//Для работы с Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Inspectors extends BaseController
{
    /* Вывод страницы*/
    public function index()
    {
      helper('form');
      echo view('/header');
      echo view('/sidebar');
      echo view('/inspectors');
      echo view('/footer');
    }
    /************************/
    /* Удаление записи по id*/
    public function delete($id)
    {
      $modelInspector = new M_inspectors();
      // Поиск записи с данным id и удаление
      $modelInspector->where('Id_ins', $id)->delete($id);

      $session = \Config\Services::session();

      $session->setFlashdata('success_ins', 'Данная запись удалена!');

      return $this->response->redirect(site_url('/'));
    }
    /*************************/
    /*Импорт из Excel*/
	  public function import()
	 {
         helper('form');
         $modelInspector = new M_inspectors();
         $modelAuthority = new M_authority();
         $modelSbe = new M_sbe();

		     $file = $this->request->getFile('file_excel');
		     $ext = $file->getClientExtension();

		     if($ext == 'xls'){
			             $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		          }else {
			             $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		          }

		    $spreadsheet = $reader->load($file);
		    $sheet = $spreadsheet->getActiveSheet()->toArray();
    /* Поиск инофрмации и добавление в БД*/
		foreach ($sheet as $x => $excel) {
			if($x == 0){
				continue;
			}

      $row1 = $modelSbe->sbeNameToId($excel['1']); 
      $row2 = $modelAuthority->auNameToId($excel['2']); 
      $excelField1 = 0;
      $excelField2 = 0;

      if(!(empty($row1))){
        $excelField1 = $row1->Id_sbe;
      }
      if(!(empty($row2))){
        $excelField2 = $row2->Id_au;
      }
		// Передача данных в модель
			try{
        $data = [
				       'SbeId' => $excelField1,
				       'AuthorityId' => $excelField2,
				       'DateFr' => date("Y-m-d", strtotime($excel['3'])),
				       'DateTo'=> date("Y-m-d", strtotime($excel['4'])),
				       'Duration' => $excel['5'],
			];}catch(\Throwable $e){
        continue;
      }
      /* Валидация Excel данных */
      if(($result = $modelInspector->validationExcel($data)) == 0){
        continue;
      }else { /* Добавление данных*/
        $data = $modelInspector->insert($data);
      }
		}
    /*********************************************/
		$session = \Config\Services::session();
		$session->setFlashdata('success_ins', 'Данные загружены');
		return redirect()->to('/');
	}

  /*********************************************/
  /* Вывод страницы создания*/
  public function get_create()
  {
    $modelInspector = new M_inspectors();
    $modelAuthority = new M_authority();
    $modelSbe = new M_sbe();

    $data = [
          'inspectors' => $modelInspector->getInspetInfo(),
          'sbe' => $modelSbe->getSbeInfo(), 
          'authority' => $modelAuthority->getAuInfo(), 
    ];
    echo view('/header');
    echo view('/sidebar');
    echo view('/create_ins',$data);
    echo view('/footer');
  }
  /* Функция создания записи Inspectors */
  public function create()
  {
        $session = \Config\Services::session();
        
        $modelInspector = new M_inspectors();
        $modelAuthority = new M_authority();
        $modelSbe = new M_sbe();

        $data = [
          'inspectors' => $modelInspector->getInspetInfo(),
          'sbe' => $modelSbe->getSbeInfo(), 
          'authority' => $modelAuthority->getAuInfo(),
        ];

        helper('form');
        /* Поиск дубля*/
        $idDouble = $modelInspector->where('SbeId',$this->request->getVar('sbe'))
                                ->where('AuthorityId',$this->request->getVar('authority'))
                                ->where('DateFr',$this->request->getVar('dateFrom'))
                                ->where('DateTo',$this->request->getVar('dateTo'))
                                ->where('Duration',$this->request->getVar('duration'))
                                ->limit(1)
                                ->findAll();
       $row = -1;
       if (!empty($idDouble[0]['Id_ins']))
       {
         $row = $idDouble[0]['Id_ins'];
       }
       /*********************************/
       /* Правила валидации */
        $rules = [

             'duration' => 'required|integer|max_length[3]|greater_than[0]',
             'sbe' => 'is_unique_all['.$row.']',
             'dateFrom' => 'timestring_less_than['.$this->request->getVar("dateTo").']',
                ];

      $errorMessages = [
           'duration' => [
                 'required' => 'Укажите плановую длительность',
                 'integer' =>  'Плановая длительность - число',
                 'max_length' => 'Значение плановой длительности слишком большое',
                 'greater_than' => 'Плановая длительность не может быть отрицательной!',
            ],
           'sbe' => [
                 'is_unique_all' => 'Запись с такими данными уже есть! ID: '.$row.'',
            ],
           'dateFrom' => [
             'timestring_less_than' => 'Укажите корректный плановый период',

            ],
    ];
    /*********************************/
            if (!$this->validate($rules, $errorMessages)){
              $errors  = \Config\Services::validation()->listErrors();
              $session->setFlashdata('warning', $errors);

              echo view('/header');//вместо этого get_create
              echo view('/sidebar',$data);
              echo view('/create_ins');
              echo view('/footer');
            }
            else {
              $modelInspector->save(
                [
                'SbeId' => $this->request->getVar('sbe'),
                'AuthorityId' => $this->request->getVar('authority'),
                'Duration' => $this->request->getVar('duration'),
                'DateFr' => $this->request->getVar('dateFrom'),
                'DateTo' => $this->request->getVar('dateTo')
              ]
              );

              $session->setFlashdata('success_ins','Запись успешно добавлена!');

              return redirect()->to('/inspectors');
            }

      }
  /* Вывод страницы редактирования*/
  public function get_edit($id)
  {
    $modelInspector = new M_inspectors();
    $modelAuthority = new M_authority();
    $modelSbe = new M_sbe();

    $data = [
          'inspectors' => $modelInspector->getInspetOnly($id), //баг
          'sbe' => $modelSbe->getSbeInfo(),
          'authority' => $modelAuthority->getAuInfo(),
    ];

    echo view('/header');
    echo view('/sidebar');
    echo view('/edit_ins',$data);
    echo view('/footer'); 
  }
  /* Функция редактирование данных*/
 public function edit_validation($id,$id_sbe)
 {
    helper(['form', 'url']);
    $session = \Config\Services::session();

    $modelInspector = new M_inspectors();
    $modelAuthority = new M_authority();
    $modelSbe = new M_sbe();

    $data = [
      'inspectors' => $modelInspector->getInspetOnly($id),
      'sbe' => $modelSbe->getSbeInfo(),
      'authority' => $modelAuthority->getAuInfo(),
    ];
    /* Поиск дубля*/
    $idDouble = $modelInspector->where('SbeId',$this->request->getVar('sbe'))
                                ->where('AuthorityId',$this->request->getVar('authority'))
                                ->where('DateFr',$this->request->getVar('dateFrom'))
                                ->where('DateTo',$this->request->getVar('dateTo'))
                                ->where('Duration',$this->request->getVar('duration'))
                                ->limit(1)
                                ->findAll();
   $row = -1;
   if (!empty($idDouble[0]['Id_ins']))
   {
     $row = $idDouble[0]['Id_ins'];
   }
   /*********************************/
   /* Правила валидации */
    $rules = [

         'duration' => 'required|integer|max_length[2]',
         'sbe' => 'is_unique_all['.$row.']',
         'dateFrom' => 'timestring_less_than['.$this->request->getVar("dateTo").']',
            ];

    $errorMessages = [
       'duration' => [
             'required' => 'Укажите плановую длительность',
             'integer' =>  'Плановая длительноsсть - число',
             'max_length' => 'Значение плановой длительности слишком большое',
        ],
       'sbe' => [
             'is_unique_all' => 'Запись с такими данными уже есть! ID: '.$row.'',
        ],
       'dateFrom' => [
         'timestring_less_than' => 'Укажите корректный плановый период',

        ],
   ];
   /*********************************/
   /*Проверка валидации*/
    if (!$this->validate($rules, $errorMessages)){
      $errors  = \Config\Services::validation()->listErrors();
      $session->setFlashdata('warning', $errors);

     echo view('/header'); //реализовать через get_edit
     echo view('/sidebar');
     echo view('/edit_ins',$data);
     echo view('/footer');
   }
   /*********************************/
   /*Сохранение данных*/
    else {

     $data = [
       'SbeId' => $this->request->getVar('sbe'),
       'AuthorityId' => $this->request->getVar('authority'),
       'Duration' => $this->request->getVar('duration'),
       'DateFr' => $this->request->getVar('dateFrom'),
       'DateTo' => $this->request->getVar('dateTo')
     ];
     $modelInspector->update($id, $data);

     $session->setFlashdata('success_ins','Запись успешно отредактирована!');

     return $this->response->redirect(site_url('/'));
    }
   /*********************************/
  }
  /*Вывод таблицы*/
  public function table_data()
  {
    $modelInspector = new M_inspectors();
    
    /*Filters*/
    $idF = $this->request->getPost('idF');
    $sbe_nameF = $this->request->getPost('sbe_nameF');
    $au_nameF = $this->request->getPost('au_nameF');
    $date_frF = $this->request->getPost('date_frF');
    $date_toF = $this->request->getPost('date_toF');
    $durationF = $this->request->getPost('durationF');
    /*********/

    $listing = $modelInspector->get_datatables($idF,$sbe_nameF,$au_nameF,$date_frF,$date_toF,$durationF);
    $count_all = $modelInspector->Count_all();
    $count_filter = $modelInspector->Count_filter($idF,$sbe_nameF,$au_nameF,$date_frF,$date_toF,$durationF);

    $data = array();
    $no = $_POST['start'];
    foreach ($listing as $key => $value) {
      $no++;
      $row = array();
      $row[] = $value->Id_ins;
      $row[] = $value->TitleS;
      $row[] = $value->TitleA;
      $row[] = $value->DateFr;
      $row[] = $value->DateTo;
      $row[] = $value->Duration;
      $row[] = '
      <a type="button" onclick="delete_data('.$value->Id_ins.')" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash-fill"></i></a>
      <a href="/edit_ins/'.$value->Id_ins.'" class="btn btn-outline-primary btn-sm" type="button"><i class="bi bi-pencil-square"></i></a>
      ';
      $data[] = $row;
    }
    $output = array(
      "draw" => $this->request->getPost('draw'),
      "recordsTotal" => $count_all->countData,
      "recordsFiltered" => $count_filter->countData,
      "data" => $data
    );
    echo json_encode($output);
  }
  /********************************/
}
