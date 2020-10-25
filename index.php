<?php
// On démarre une session
session_start();

// On inclut la connexion à la base
require_once('connect.php');

// selection de toutes les colonnes de la table category et articles avec jointure de category.id 
$sql = "SELECT * FROM category, articles  WHERE articles.category_id = category.id ORDER BY created_at DESC";
// On prépare la requête
$query = $db->prepare($sql);

// On exécute la requête
$query->execute();

// On stocke le résultat dans un tableau associatif
$articles = $query->fetchAll(PDO::FETCH_ASSOC);





require_once('close.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>articles</title>

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
                <?php
                    if(!empty($_SESSION['message'])){
                        echo '<div class="alert alert-success" role="alert">
                                '. $_SESSION['message'].'
                            </div>';
                        $_SESSION['message'] = "";
                    }
                ?>
                <h1>articles </h1>
                <table class="table">
                    <thead>
                        <th>ID</th>
                        <th>title</th>
                        <th>content</th>
                        <th>date de création</th>
                        <th>date de modif</th>
                        <th>catégorie</th>
                        <th>action</th>
                    </thead>
                    <tbody>
                        <?php
                        // On boucle sur la variable articles
                        foreach($articles as $article){
                        ?>
                            <tr>
                                <td><?= $article['id'] ?></td>
                                <td><?= $article['title'] ?></td>
                                <td><?= $article['content'] ?></td>
                                <td><?= $article['created_at'] ?></td>
                                <td><?= $article['updated_at'] ?></td>
                                <td><?= $article['category_name'] ?></td>
                                <td> <a href="detailArticle.php?id=<?= $article['id'] ?>">Voir</a> <a href="updateArticle.php?id=<?= $article['id'] ?>">Modifier</a> <a href="deleteArticle.php?id=<?= $article['id'] ?>">Supprimer</a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <a href="createArticle.php" class="btn btn-primary">Ajouter un article</a>
                <a href="listcategory.php" class="btn btn-primary">Voir les catégories</a>
            </section>
        </div>
    </main>
</body>
</html>