<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mon blog</title>
</head>

<body>
<div>
    <h1>Mon blog</h1>
    <p>En construction</p>
    <div>
        <h2><?= htmlspecialchars($post->gettitle());?></h2>
        <p><?= htmlspecialchars($post->getdescription());?></p>
        <p><?= htmlspecialchars($post->getauthor());?></p>
        <p>Créé le : <?= htmlspecialchars($post->getcreatedAt());?></p>
    </div>
    <br>
    <a href="../public/index.php">Retour à l'accueil</a>
    <div id="comments" class="text-left" style="margin-left: 50px">
        <h3>Commentaires</h3>
        <a href="../public/index.php?route=addComment&postId=<?= htmlspecialchars($post->getid());?>">Nouveau commentaire</a>
        <?php
        foreach ($comments as $comment) {
            ?>
            <h4><?= htmlspecialchars($comment->getpseudo());?></h4>
            <p><?= htmlspecialchars($comment->getdescription());?></p>
            <p>Posté le <?= htmlspecialchars($comment->getcreatedAt());?></p>
            <?php
        }
        ?>
    </div>
</div>
</body>
</html>