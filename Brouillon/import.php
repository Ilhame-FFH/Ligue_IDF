<?php

//import.php

include 'vendor/autoload.php';
include('head.php');

$connect = new PDO("mysql:host=localhost;dbname=ligue_idf", "root", "");

if($_FILES["import_excel"]["name"] != '')
{
 $allowed_extension = array('xls', 'xlsx');
 $file_array = explode(".", $_FILES["import_excel"]["name"]);
 $file_extension = end($file_array);

 if(in_array($file_extension, $allowed_extension))
 {

  $file_name = time() . '.' . $file_extension;
  move_uploaded_file($_FILES['import_excel']['tmp_name'], $file_name);
  $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

  $spreadsheet = $reader->load($file_name);

  unlink($file_name);
  $data = $spreadsheet->getActiveSheet()->toArray();
  //var_dump($data);

  foreach($data as $row)
  {
   $insert_data = array(
    ':formation'  => $row[0],
    ':session'  => $row[1],
    ':statut'  => $row[2],
    ':nom'  => $row[3]
   );
   //var_dump($insert_data);
   $query = "INSERT INTO stagiaire 
   (formation, session, statut, nom) 
   VALUES (:formation, :session, :statut, :nom)";

   $statement = $connect->prepare($query);
   var_dump($statement);

   //$statement->execute($insert_data);
  }
  $message = '<div class="alert alert-success">Données ont bien été importées</div>';

 }
 else
 {
  $message = '<div class="alert alert-danger">Uniquement les fichiers .xls .csv or .xlsx </div>';
 }
}
else
{
 $message = '<div class="alert alert-danger">Veuillez selectionner un fichier </div>';
}

echo $message;

?>