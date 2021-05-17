<?php
$dossier = 'upload/'; //* Je crée un dossier pour herberger mes fichier temporaire
$fichier = basename($_FILES['monfichier']['name']);
$taille_maxi = 200000;
$taille = filesize($_FILES['monfichier']['tmp_name']);
$extensions = array('.csv', '.txt'); //*Je crée mes extentions
$extension = strrchr($_FILES['monfichier']['name'], '.'); 
//Début des vérifications de sécurité...
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
     $erreur = '';
}
if($taille>$taille_maxi)
{
     $erreur = 'Le fichier est trop gros...';
}
if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
{
    //On formate le nom du fichier ici...
    $fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     
    if(move_uploaded_file($_FILES['monfichier']['tmp_name'], $dossier . $fichier)){

            // Je lis mon fichier csv
            $row = 1;   
            if (($handle = fopen("upload/".$fichier, "r")) !== FALSE) {
                $delimiteur = explode("_" , $fichier);
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    $num = count($data);     
                    $requette=$db->prepare("INSERT INTO `hardbounce_projet` VALUES ( :monfichier ) ");
                    $requette->bindParam(':monfichier',$_FILES['monfichier']);
                    $requette->execute();
                    $resultat=$requette->fetchAll(PDO::FETCH_ASSOC);
                    print_r($resultat);
                    echo "<p> $num champs à la ligne $row: <br /></p>\n";
                    $row++;

                    for ($c=0; $c < $num; $c++) {
                        echo $data[$c] . "<br />\n";
                    }
                }
                fclose($handle);
            }
  echo '<div class="container-fluid">'
            . '<div class="row">'
            . '<div class="col-lg-12 sucess_envoi">'
            . '<span class="">Upload effectué avec succès</span>!'
            . '</div>'
            . '</div>'
       .'</div>';
    
    
    }else{
          echo '<span class="echec_envoi">Echec de l\'upload !</span>';
     }
}
else
{
     echo $erreur;
}
?>

<div class="form-group">
				<input type="hidden" name="MAX_FILE_SIZE" value="104857600" /> <!-- Limite 100Mo -->
				<input type="file" class="form-control" name="monfichier" id="monfichier" />
			</div>

			<input type="submit" class="btn btn-primary" name="ajouter" value="Ajouter"><br/>