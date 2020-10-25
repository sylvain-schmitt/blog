<?php
// On démarre une session
session_start();

// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['id']) && !empty($_GET['id'])){
    require_once('connect.php');

    // On nettoie l'id envoyé
    $id = strip_tags($_GET['id']);

    $sql = "SELECT * FROM category WHERE id = :id";

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':id', $id, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    // On récupère le article
    $category = $query->fetch();

    // On vérifie si le article existe
    if(!$category){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: index.php');
        die();
    }

    $sql = "DELETE FROM category WHERE id = :id";

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':id', $id, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();
    $_SESSION['message'] = "Catégorie supprimé";
    header('Location: index.php');


}else{
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index.php');
}
