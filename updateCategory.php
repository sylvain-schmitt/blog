<?php
// On démarre une session
session_start();
require('function.php');
$categorys = selectCategory();


if($_POST){
    if(isset($_POST['id']) && !empty($_POST['id'])
    && isset($_POST['category']) && !empty($_POST['category'])){
        // On inclut la connexion à la base
        require_once('connect.php');

        // On nettoie les données envoyées
        $id = strip_tags($_POST['id']);
        $category = strip_tags($_POST['category']);
        //fonction permettant de générer un slug 
        $slug = slugify($category);


        $sql = "UPDATE category SET category_name = :category, slug = :slug WHERE id = :id";
 
        $query = $db->prepare($sql);

        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':category', $category, PDO::PARAM_STR);
        $query->bindValue(':slug', $slug, PDO::PARAM_STR);

        $query->execute();


        $_SESSION['message'] = "Catégorie modifié";
        require_once('close.php');

        header('Location: index.php');
    }else{
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

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

    // On récupère le title
    $category = $query->fetch();

    // On vérifie si le title existe
    if(!$category){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: index.php');
    }
}else{
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Catégorie</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php
                    if(!empty($_SESSION['erreur'])){
                        echo '<div class="alert alert-danger" role="alert">
                                '. $_SESSION['erreur'].'
                            </div>';
                        $_SESSION['erreur'] = "";
                    }
                ?>
                <h1>Modifier une catégorie</h1>
                <form method="POST">
                    <div class="form-group">
                        <label for="category">Nom</label>
                        <input type="text" id="category" name="category" class="form-control" value="<?= $category['category_name']?>">
                    </div>
                    <input type="hidden" value="<?= $category['id']?>" name="id">
                    <button class="btn btn-primary">Envoyer</button>
                </form>
            </section>
        </div>
    </main>
</body>
</html>