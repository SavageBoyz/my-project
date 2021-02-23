<?php
/*
*
* Controller для работы с данными СМП
*
*/
namespace App\Controllers;

use App\Models\M_sbe;

class Sbe extends BaseController
{
  /* Вывод страницы*/
    public function index()
    {
      $modelSbe = new M_sbe();
      $data['sbe'] = $modelSbe->getSbeInfo();
      echo view('/header');
      echo view('/sidebar');
      echo view('/sbe',$data);
      echo view('/footer');
    }
    /* Удаление записи по id*/
    public function delete($id)
    {
      $modelSbe = new M_sbe();
      // Поиск записи с данным id и удаление
      $modelSbe->where('Id_sbe', $id)->delete($id);

      $session = \Config\Services::session();

      $session->setFlashdata('success_sbe', 'Данная запись удалена!');

      return $this->response->redirect(site_url('/sbe'));
    }
    /*************************/
    /* Вывод страницы редактирования*/
  public function get_edit($id)
  {
    $modelSbe = new M_sbe();
    $data = [
          'sbe' => $modelSbe->getSbeOnly($id),
    ];

    echo view('/header');
    echo view('/sidebar');
    echo view('/edit_sbe',$data);
    echo view('/footer');
 }
    /*************************/
/* Функция редактирования данных */
 public function edit_validation($id)
 {
    helper(['form', 'url']);
    $session = \Config\Services::session();

    $modelSbe = new M_sbe();

    $data = [
      'sbe' => $modelSbe->getSbeOnly($id),
    ];
   /* Правила валидации */
   $rules = [

        'title' => 'required|max_length[255]|min_length[3]|is_unique[sbe.TitleS]',
           ];

  $errorMessages = [
      'title' => [
            'required' => 'Укажите название СМП',
            'max_length' => 'Значение СМП слишком большое',
            'min_length' => 'Значение СМП слишком маленькое',
            'is_unique' => 'Такое название СМП уже есть в таблице'
       ],
  ];
   /*********************************/
   /*Проверка валидации*/
    if (!$this->validate($rules, $errorMessages)){
      $errors  = \Config\Services::validation()->listErrors();
      $session->setFlashdata('warning', $errors);

     echo view('/header');//Можно испльзовать get_edit
     echo view('/sidebar');
     echo view('/edit_sbe',$data);
     echo view('/footer');
   }
   /*********************************/
   /*Сохранение данных*/
    else {

     $data = [
       'TitleS' => $this->request->getVar('title'),
     ];
     $modelSbe->update($id, $data);

     $session->setFlashdata('success_sbe','Запись успешно отредактирована!');

     return $this->response->redirect(site_url('/sbe'));
    }
   /*********************************/
  }

  public function get_create()
  {
    $modelSbe = new M_sbe();
    $data = [
          'sbe' => $modelSbe->getSbeInfo(),
    ];
    echo view('/header');
    echo view('/sidebar');
    echo view('/create_sbe',$data);
    echo view('/footer');
  }
  /* Функция создания записи СМП */
  public function create()
  {
        $session = \Config\Services::session();
        $modelSbe = new M_sbe();
        $data = [
              'sbe' => $modelSbe->getSbeInfo(),
        ];

        helper('form');
       /* Правила валидации */
        $rules = [

             'title' => 'required|max_length[255]|min_length[3]|is_unique[sbe.TitleS]',
                ];

      $errorMessages = [
           'title' => [
                 'required' => 'Укажите название СМП',
                 'max_length' => 'Значение СМП слишком большое',
                 'min_length' => 'Значение СМП слишком маленькое',
                 'is_unique' => 'Такое название СМП уже есть в таблице'
                  ],
                    ];
    /*********************************/
            if (!$this->validate($rules, $errorMessages)){
              $errors  = \Config\Services::validation()->listErrors();
              $session->setFlashdata('warning', $errors);

              echo view('/header'); // Заменить функцией get_create
              echo view('/sidebar',$data);
              echo view('/create_sbe');
              echo view('/footer');
            }
            else {
              $modelSbe->save(
                [
                'TitleS' => $this->request->getVar('title'),
              ]
              );

              $session->setFlashdata('success_sbe','Запись успешно добавлена!');

              return redirect()->to('/sbe');
            }

      }
}
