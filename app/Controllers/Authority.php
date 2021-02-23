<?php
/*
*
* Контроллер вывода информации о контр. органах и удаления
*
*/
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_authority;

class Authority extends BaseController
{
  /* Вывод страницы*/
    public function index()
    {
      $modelAuthority = new M_authority();
      $data['authority'] = $modelAuthority->getAuInfo();
      echo view('/header');
      echo view('/sidebar');
      echo view('/authority',$data);
      echo view('/footer');
    }
    /* Удаление записи по id*/
    public function delete($id)
    {
      $modelAuthority = new M_authority();
      // Поиск записи с данным id и удаление
      $modelAuthority->where('Id_au', $id)->delete($id);

      $session = \Config\Services::session();

      $session->setFlashdata('success_au', 'Данная запись удалена!');

      return $this->response->redirect(site_url('/authority'));
    }
    /*************************/
    /* Вывод страницы создания*/
  public function get_create()
  {
    $modelAuthority = new M_authority();
    $data = [
          'authority' => $modelAuthority->getAuInfo(),
    ];
    echo view('/header');
    echo view('/sidebar');
    echo view('/create_au',$data);
    echo view('/footer');
  }
  /*************************/
  /* Функция создания контр. орган */
  public function create()
  {
        $session = \Config\Services::session();
        $modelAuthority = new M_authority();
        $data = [
              'authority' => $modelAuthority->getAuInfo(),
        ];

        helper('form');
       /* Правила валидации */
        $rules = [
             'title' => 'required|max_length[255]|min_length[3]|is_unique[authority.TitleA]',
                ];

      $errorMessages = [
           'title' => [
                 'required' => 'Укажите название контр. органа',
                 'max_length' => 'Значение контр. органа слишком большое',
                 'min_length' => 'Значение контр. органа слишком маленькое',
                 'is_unique' => 'Такое название контр. органа уже есть в таблице'
                   ],
                    ];
      /*********************************/
            if (!$this->validate($rules, $errorMessages)){
              $errors  = \Config\Services::validation()->listErrors();
              $session->setFlashdata('warning', $errors);

              echo view('/header');
              echo view('/sidebar',$data);
              echo view('/create_au');
              echo view('/footer');
            }
            else {
              $modelAuthority->save(
                [
                'TitleA' => $this->request->getVar('title'),
              ]
              );

              $session->setFlashdata('success_au','Запись успешно добавлена!');

              return redirect()->to('/authority');
            }

      }
      /*************************/
      /* Вывод страницы*/
  public function get_edit($id)
  {
    $modelAuthority = new M_authority();
    $data = [
          'authority' => $modelAuthority->getAuOnly($id),
    ];

    echo view('/header');
    echo view('/sidebar');
    echo view('/edit_au',$data);
    echo view('/footer');
 }
 /*************************/
 /*Функция редактирования данных*/
 public function edit_validation($id)
 {
  helper(['form', 'url']);
  $session = \Config\Services::session();

  $modelAuthority = new M_authority();

  $data = [
    'authority' => $modelAuthority->getAuOnly($id),
  ];
 /* Правила валидации */
 $rules = [

      'title' => 'required|max_length[255]|min_length[3]|is_unique[authority.TitleA]',
         ];

$errorMessages = [
    'title' => [
          'required' => 'Укажите название контр. органа',
          'max_length' => 'Значение контр. органа слишком большое',
          'min_length' => 'Значение контр. органа слишком маленькое',
          'is_unique' => 'Такое название контр. органа уже есть в таблице'
     ],
];
 /*********************************/
 /*Проверка валидации*/
  if (!$this->validate($rules, $errorMessages)){
    $errors  = \Config\Services::validation()->listErrors();
    $session->setFlashdata('warning', $errors);

   echo view('/header');
   echo view('/sidebar');
   echo view('/edit_au',$data);
   echo view('/footer');
 }
 /*********************************/
 /*Сохранение данных*/
  else {
   $data = [
     'TitleA' => $this->request->getVar('title'),
   ];
   $modelAuthority->update($id, $data);

   $session->setFlashdata('success_au','Запись успешно отредактирована!');

   return $this->response->redirect(site_url('/authority'));
  }
 /*********************************/
  }
}
