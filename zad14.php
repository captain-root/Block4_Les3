<!Doctype html>
<html>
<head>
  <link href="table.css" rel="stylesheet">
  <meta charset="utf-8">
  <title>Дела</title>
</head>
<h1> Список дел </h1>
<?php
// Готовим кнопку
  if  (isset($_GET['action']) and ($_GET['action'] == 'edit')) {
      $codeButton = "<button type = 'submit' name = 'edit'>Изменить</button>";
      } else {
           $codeButton = "<button type = 'submit' name = 'added'>Добавить</button>" ;
  }
?>
<form method = 'POST'>
   <input type = 'text' name = 'description' placeholder = "описание задачи">
   <?= $codeButton ?>
   Сортировать по: <select name = "sorting" size = "1">
                        <option value = "date_added">дате добавления</option>
                        <option value = "status">статусу</option>
                        <option value = "description">описанию</option>
                   </select>
   <button type = 'submit' name = 'sort'>Отсортировать</button>
</form>
<?php
$listTask = new PDO('mysql:host=localhost; dbname=saltanov; charset=utf8', '*******', '*******');
$listTask->query('SET NAMES utf8');
if ((isset($_POST['added'])) and (isset($_POST['description']))) {
   if ($_POST['description'] !== ''){
       $sql = "INSERT INTO tasks (description,is_done,date_added) VALUES (:descr,0,CURRENT_TIMESTAMP);";
       $stm = $listTask->prepare($sql);
       $stm -> bindParam(':descr',$description);
       $description = $_POST['description'];
       $stm->execute();
   }
}
  if ((isset($_GET['action'])) and (isset($_GET['id']))) {
     if ($_GET['action'] == 'delete'){
         $sql = "DELETE FROM tasks WHERE ID = :id;";
         $stm = $listTask->prepare($sql);
         $stm -> bindParam(':id',$idrecord);
         $idrecord = $_GET['id'];
         $stm->execute();
     }

     if (isset($_POST['edit']) and ($_GET['action'] == 'edit')){
         $sql = "UPDATE tasks SET description = :descr WHERE ID = :id;";
         $stm = $listTask->prepare($sql);
         $stm = $listTask->prepare($sql);
         $stm -> bindParam(':descr',$description);
         $description = $_POST['description'];
         $stm -> bindParam(':id',$idrecord);
         $idrecord = $_GET['id'];
         $stm->execute();
     }
      if ($_GET['action'] == 'done'){
          $sql = "UPDATE tasks SET is_done = 1 WHERE ID = :id;";
          $stm = $listTask->prepare($sql);
          $stm -> bindParam(':id',$idrecord);
          $idrecord = $_GET['id'];
          $stm->execute();
      }
  }
 ?>
<br>
<table>
<tr>
  <th>Описание задачи</th>
  <th>Дата добавления</th>
  <th>Статус</th>
  <th> </th>
</tr>
<?php
  $sql = "SELECT * FROM tasks";
  if ((isset($_POST['sort'])) and (isset($_POST['sorting']))) {
       if ($_POST['sorting'] == 'date_added') {
       $sql = "SELECT * FROM tasks ORDER BY date_added ";
       }
  }
  if ((isset($_POST['sort'])) and (isset($_POST['sorting']))) {
       if ($_POST['sorting'] == 'is_done') {
       $sql = "SELECT * FROM tasks ORDER BY is_done ";
       }
  }
  if ((isset($_POST['sort'])) and (isset($_POST['sorting']))) {
       if ($_POST['sorting'] == 'description') {
       $sql = "SELECT * FROM tasks ORDER BY description ";
       }
  }
  $result = $listTask->query($sql);
   foreach ($result as $items) {
     if ($items['is_done']==0){
         $done = 'В процессе';
         }
         else{
             $done = 'Выполнено';
         }
         $is_delete = '?id='.$items['id'].'&action=delete';
         $is_done   = '?id='.$items['id'].'&action=done';
         $is_edit   = '?id='.$items['id'].'&action=edit';
?>
<tr>
  <td><?= $items['description']?></td>
  <td><?= $items['date_added']?></td>
  <td><?= $done ?></td>
  <td><a href="<?= $is_edit ?>">Изменить</a><a href="<?= $is_done ?>"> Выполнить</a> <a href="<?= $is_delete ?>"> Удалить</a> </td>
</tr>
<?php }
?>
</table>
</html>
