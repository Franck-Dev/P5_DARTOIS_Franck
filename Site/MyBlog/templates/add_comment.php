<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mon blog</title>
</head>

<body>
    <div>
        <h1>Mon blog</h1>
        <?php $this->title = "Nouveau commentaire"; ?>
            <p>Formulaire de création d'un commentaire (à intégrer au post, avant les commentaires)</p>
            <div>
                <form method="post" action="../public/index.php?route=addComment&postId=<?=htmlspecialchars($_GET['postId']);?>">
                    <label for="description">Contenu</label><br>
                    <textarea id="description" name="description"></textarea><br>
                    <label for="pseudo">Auteur</label><br>
                    <input type="text" id="pseudo" name="pseudo"><br>
                    <input type="text" hidden="false" id="postId" name="postId" value=<?=htmlspecialchars($_GET['postId']);?>><br>
                    <input type="submit" value="Envoyer" id="submit" name="submit">
                </form>
                <a href="../public/index.php">Retour à l'accueil</a>
            </div>
        </div>
</body>
</html>