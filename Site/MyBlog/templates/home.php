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
        <a href="../public/index.php?route=addPost">Nouvel article</a>
        <?php
        //var_dump($posts);
        foreach ($posts as $post) {
            ?>
            <div>
            <h2><a href="../public/index.php?route=post&postId=<?= htmlspecialchars($post->getid());?>"><?= htmlspecialchars($post->gettitle());?></a></h2>
                <p><?= htmlspecialchars($post->getdescription());?></p>
                <p><?= htmlspecialchars($post->getauthor());?></p>
                <p>Créé le : <?= htmlspecialchars($post->getcreatedAt());?></p>
            </div>
            <br>
            <?php
        }
        ?>
    </div>
</body>
</html>