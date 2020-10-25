<?php
// On démarre une session
session_start();


// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['id']) && !empty($_GET['id'])){
    require_once('connect.php');


    // On nettoie l'id envoyé
    $id = strip_tags($_GET['id']);

    $sql = "SELECT * FROM  articles  WHERE  id = :id ";

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':id', $id, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    // On récupère l'article'
    $article = $query->fetch();

    $cat_id = $article['category_id'];
    $category = "SELECT category_name FROM category WHERE id = $cat_id";
    $req = $db->prepare($category);
    $req->execute();
    $cat = $req->fetch();


    // On vérifie si l'article existe
    if(!$article){
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
    <title>Détails de l'article</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <main class="container">
        
        <div class="card text-center">
            <div class="card-header">
            <p>Dans le catégorie <?= $cat['category_name'] ?></p>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $article['title'] ?></h5>
                <p class="card-text"><?= $article['content'] ?></p>
                <a href="index.php" class="btn btn-primary">Retour</a>
                <a href="updateArticle.php?id=<?= $article['id'] ?>" class="btn btn-primary">Modifier</a>
            </div>
            <div class="card-footer text-muted">
            <p>écrit le <?=$article['created_at'] ?></p>
            </div>
            </div>
    </main>
    <div class="container text-center">
            <img src="https://picsum.photos/600" class="img-fluid" alt="Responsive image">
            </div>

</body>
</html>