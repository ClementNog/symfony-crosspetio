<?php

$serveur = "localhost";
$dbname = "crosspetio";
$user = "root";
$pass = "";

date_default_timezone_set('Europe/Paris');

try{
    $connexion = new PDO("mysql:host=$serveur;dbname=$dbname",$user,$pass);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_GET["identifiant"])) {
        $identifiant = $_GET["identifiant"];
        $end = date("H:i:s");     
        $requete = "SELECT START FROM depart ORDER BY id DESC LIMIT 1";
        $stm = $connexion->query($requete);
        $result = $stm->fetch();
        $start = $result[0];
        error_log("Heure de départ = '".$start."'");
        error_log("L'élève avec le dossard n° " . $identifiant . " vient d'arriver à " .$end);        
        // Enregistrer l'heure d'arrivée de cet élève
        //$requete = "INSERT INTO eleve (identifiant) VALUES ('$identifiant');";
        $requete = "INSERT INTO `student`( `id`, `end`, start) VALUES(:identifiant, :end, :start)";
        $stmt = $connexion->prepare($requete);
        $stmt->bindParam(':identifiant', $identifiant);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        error_log($requete);
        $stmt->execute();               
    } else {        
        $start = date("H:i:s");
        echo("La course a démarrée à " . $start . " !");
        //error_log("La course a démarrée à " . $start . " !");
        // Enregistrer l'heure de départ de la course
        $requete = "INSERT INTO `race`( `start`) VALUES(:start)";
        $stmt = $connexion->prepare($requete);
        $stmt->bindParam(':start', $start);
        error_log($requete);
        $stmt->execute();               
    }
}
catch(PDOException $e){
    error_log('Erreur : '.$e->getMessage());
}