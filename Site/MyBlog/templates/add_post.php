<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mon blog</title>
</head>

<body>
    <div>
        <h1>Mon blog</h1>
        <?php $this->title = "Nouvel article"; ?>
            <p>Formulaire de création d'un article</p>
            <div>
                <form method="post" action="../public/index.php?route=addPost">
                    <label for="title">Titre</label><br>
                    <input type="text" id="title" name="title"><br>
                    <label for="description">Contenu</label><br>
                    <textarea id="description" name="description"></textarea><br>
                    <label for="author">Auteur</label><br>
                    <input type="text" id="author" name="author"><br>
                    <input type="submit" value="Envoyer" id="submit" name="submit">
                </form>
                <a href="../public/index.php">Retour à l'accueil</a>
            </div>
        </div>
</body>
</html>