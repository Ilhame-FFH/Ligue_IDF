
<?php
require 'vendor\autoload.php';

use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\Csv;

// Connect to database
include('head.php');

function lire_csv($nom_fichier, $separateur = ";") {
	$row = 0;
	$donnee = array();
	$f = fopen($nom_fichier, "r");
	$taille = filesize($nom_fichier) + 1;
	while ($donnee = fgetcsv($f, $taille, $separateur)) {
		$result[$row] = $donnee;
		$row++;
	}
	fclose($f);
	return $result;
}

function requete_insert($donnees_csv, $table) {
	$insert = array();
	$i = 0;
	while (list($key, $val) = @each($donnees_csv)) {
		/* On ajoute une valeur vide ' ' en début pour le champs d'auto-incrémentation  s'il existe, sinon enlever cette valeur */
		if ($i > 0) {
			$insert[$i] = "INSERT into " . $table ."(formation, session, statut, nom)". " VALUES(' ',";
			$insert[$i] .= implode("','", $val);
			$insert[$i] .= "')";
		}$i++;
	}
	return $insert;
}

if (isset($_POST["import"])) {
	$xls_file = $_FILES["import_excel"]["tmp_name"];

	$reader = new Xlsx();
	$spreadsheet = $reader->load($xls_file);

	$loadedSheetNames = $spreadsheet->getSheetNames();

	$writer = new Csv($spreadsheet);

	foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
		$writer->setSheetIndex($sheetIndex);
		$file = $loadedSheetName . '.csv';
		$writer->save($file);
	}
	$file = $file;
	echo $file;

	if (file_exists($file)) {
		$tab = file($file);
		$donnees = lire_csv($file);
		$size = count($donnees);

		for ($i = 1; $i < $size; $i++) {
			print_r($donnees[$i]) . "<br />\n";
			//$requetes = requete_insert($donnees, "stagiaire");
			//print_r($requetes);
			//$sql = "INSERT INTO stagiaire (formation, session, statut, nom) values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "')";
		}
		foreach ($requetes as $requete) {
			//$result = mysql_query($requete) or die('Erreur SQL !' . $requete . '<br />' . mysql_error());
			try {
				$result = $conn->query($requete);
			} catch (Exception $e) {
				die('Erreur SQL: ' . $e->getMessage());
			}
		}

		/* while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
		  /* $sql = "INSERT INTO stagiaire (formation, session, statut, nom)
		  values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "')";
		  //$result = mysqli_query($conn, $sql);
		  $result = $connect->prepare($sql);
		  //var_dump($statement);

		  $result->execute();

		  if (!empty($result)) {
		  $type = "success";
		  $message = "Les Données sont importées dans la base de données";
		  unlink($file_name);
		  header('Location: stagiaires.php');
		  } else {
		  $type = "error";
		  $message = "Problème lors de l'importation de données CSV";
		  }
		  } */
	}
}


//Retourner à la page index.php
?>

<html>
	<body>
		<div class="panel panel-default">
			<div class="panel-heading">Importez les données des stagiaires</div>
			<div class="panel-body">
				<div class="table-responsive">
					<span id="message"></span>
					<form method="post" id="import_excel_form" enctype="multipart/form-data">
						<table class="table">
							<tr>
								<td width="25%" align="right">Selectionne le fichier Excel</td>
								<td width="50%"><input type="file" name="import_excel" /></td>
								<td width="25%"><input type="submit" name="import" id="import" class="btn btn-primary" value="Import" /></td>
							</tr>
						</table>
					</form>
					<br />

				</div>
			</div>
		</div>
	</body>
</html>
