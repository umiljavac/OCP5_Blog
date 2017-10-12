<section>
    <div class="container">
        <div class="divLogo text-center">
            <img class="logoBody" src="img/logo3min.png" alt="logo">
        </div>

        <?php
        if($user->hasMessage() && ($_SESSION['trajet'] == 'redirect'))
        {
            echo '<p>', $user->getMessage(), '</p>';
            $_SESSION['trajet'] = 'direct';
        }
        ?>

        <div class="blogPost">
            <h2 class="titre marginTop"><?= htmlspecialchars($blogPost['titre'])?></h2>
            <p class="date">Mis à jour le <?= $blogPost['dateModif']->format('d/m/Y à H\hi')?></p>

            <p class="chapo"><?= nl2br(htmlspecialchars($blogPost['chapo']))?></p>
            <p class="contenu"><?= nl2br(htmlspecialchars($blogPost['contenu']))?></p>
            <p>Article rédigé par <span class="auteurBlogPost"><?= htmlspecialchars($blogPost['auteur'])?></span></p>
            <div class="action text-right">
                <a type="button" class="btn modifier btn-sm" href="/blogPost/update/<?= $blogPost['id']?>">Modifier</a>
                <a type="button" class="btn supprimer btn-sm" href="/blogPost/delete/<?= $blogPost['id']?>">Supprimer</a>
            </div>
        </div>
        <div class="commentaires">
            <h3 class="text-center">Commentaires</h3>
            <p><a type="button" class="btn envoyer btn-sm" href="/../comment/insert/<?= $blogPost['id']?>">Ajouter un commentaire</a></p>

            <?php
            foreach ($commentList as $comment)
            {
                ?>
                <div class="commentaire">
                    <p>par <span class="auteurCommentaire"><?= htmlspecialchars($comment->auteur())?></span> <span class="dateCommentaire">le <?= $comment->dateModif()->format('d/m/Y à H\hi')?></span>
                    </p>

                    <p class="contenuCommentaire"><?= nl2br(htmlspecialchars($comment->contenu()))?></p>
                    <div class="action text-right">
                            <a type="button" class="btn modifier btn-sm" href="/../comment/update/<?=$comment['id']?>">Modifier</a>
                            <a type="button" class="btn supprimer btn-sm" href="/../comment/delete/<?=$comment['id']?>">Supprimer</a>
                    </div>
                </div>
                <?php
            }
        ?>
        </div>
    </div>
</section>