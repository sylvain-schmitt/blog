<?php
// On démarre une session
session_start();
require_once('connect.php');
require('function.php');


$categorys = selectCategory();


if($_POST){
    if(isset($_POST['title']) && !empty($_POST['title'])
    && isset($_POST['content']) && !empty($_POST['content'])
    && isset($_POST['category']) && ($_POST['category'] != 0)){        

        // On nettoie les données envoyées
        $title = strip_tags($_POST['title']);
        $content = strip_tags($_POST['content']);
        $category = strip_tags($_POST['category']);
        //fonction permettant de générer un slug 
        $slug = slugify($title);

       
        //on prepare l'insertion des champs dans la table articles (created_at et slug sont générer automatiquement)
        $sql = "INSERT INTO articles (title, content, created_at, category_id, slug) VALUES (:title, :content, NOW(), :category, :slug)";
        $query = $db->prepare($sql);

        //on inclue des paramètre à la requète
        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->bindValue(':content', $content, PDO::PARAM_STR);
        $query->bindValue(':category', $category, PDO::PARAM_INT);
        $query->bindValue(':slug', $slug, PDO::PARAM_STR);

        $query->execute();

        $category =  $query->fetch(); 


        $_SESSION['message'] = "Article ajouté";
        require_once('close.php');

        header('Location: index.php');
    }else{
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article</title>

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
                <h1>Ajouter un article</h1>
                <form method="POST">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text"  name="title" class="form-control">
                    </div>
                    <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Contenu de l'article</span>
                    </div>
                        <textarea name="content" class="form-control" aria-label="With textarea"></textarea>
                    </div><br><hr>
                    <div class="form-group">
                        <select class="form-control" name="category">
                            <option type="text" value="<?= $category['category_id']?>">Choisir une Catégorie</option>
                            <?php foreach($categorys as $category ): ?>
                            <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-primary">Envoyer</button>
                </form>
            </section>
        </div>
    </main>
</body>
</html>