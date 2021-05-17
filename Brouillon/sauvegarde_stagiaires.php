<?php
include('head.php');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$req = 'select * from ligue_idf.session_certif s where id_session=' . $_GET['id'] . ';';
$result = $conn->prepare($req);
try {
	$result->execute();
} catch (PDOException $e) {
	echo $e->getMessage();
}
$donnees = $result->fetchAll();
foreach ($donnees as $value) {
}
if (isset($_POST['importer'])) {
	$fichier = fopen("assets/excel/Modele_excel_stagiaires.xls", "r");
	while (!feof($fichier)) {
		$var = fgets($fichier, 4096);
	}
	echo $var;
	fclose($fichier);
}
?>
<html>
	<body>
		<!-- Fixed navbar -->	
		<div class="navbar navbar-inverse navbar-fixed-top headroom" >
			<div class="container">
				<div class="navbar-header">
					<!-- Button for smallest screens -->
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
					<a class="navbar-brand" href="index.html"><img src="assets/images/coq_ffh_.png" width="50" height="50" alt="Ligue IDF"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav pull-right">
						<li class="active"><a href="session.php">Sessions</a></li>
						<li><a class="btn" href="deconnexion.php">DECONNEXION</a></li>
					</ul>
				</div>
			</div>
		</div> 

		<header id="head" class="secondary"></header>

		<div class="container">
			<div class="row">
				<article class="col-xs-12 maincontent">
					<header class="page-header">
						<h1 class="page-title">Liste des stagiaires de la session <?= $value['libelle_session'] ?></h1>
					</header>
					<div class="alert alert-danger" role="alert">
						<h1>Attention</h1> Télécharger le modele .xlsx et avant de l'importer Veuillez vous assurer que le fichier est en .xlsx et qu'il comporte les colonnes " statut, nom,	prenom,	date_naissance,	courriel, telephone, convoque_certification, resultats_certification, commentaires_certification.
						<a href="assets/excel/Modele_excel_stagiaires.xlsx" type="application/msexcel">
							<button type="button" class="btn btn-primary" name="modeleExcel">Telecharger le modele Excel</button>
						</a>
					</div>
					<!--<form method="POST" enctype="multipart/form-data">
						<input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
						<input type="file" class="form-control" name="monfichier" id="monfichier" required/></br>
						<button type="submit" class="btn btn-primary" name="importer">Importer stagiaires</button>
					</form>-->

					<!--<form>
						<div class="form-group">
							<label for="exampleFormControlFile1">Import des stagiaires</label>
							<input type="file" class="form-control-file" id="exampleFormControlFile1"></br>
							<button type="submit" class="btn btn-primary" name="importer">OK</button>
						</div>
					</form>-->
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
			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

			<br/>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Statut</th>
						<th scope="col">Nom</th>
						<th scope="col">Prenom</th>
						<th scope="col">Date de naissance</th>
						<th scope="col">Courriel</th>
						<th scope="col">Telephone</th>
						<th scope="col">Convoqué Certification</th>
						<th scope="col">Certification</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>	
					</tr>
				</tbody>
			</table>
		</article>
	</div>
</div>
</body>
</html>
<script>
	$(document).ready(function () {
		$('#import_excel_form').on('submit', function (event) {
			event.preventDefault();
			$.ajax({
				url: "import.php",
				method: "POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				beforeSend: function () {
					$('#import').attr('disabled', 'disabled');
					$('#import').val('Importing...');
				},
				success: function (data)
				{
					$('#message').html(data);
					$('#import_excel_form')[0].reset();
					$('#import').attr('disabled', false);
					$('#import').val('Import');
				}
			})
		});
	});
</script>
