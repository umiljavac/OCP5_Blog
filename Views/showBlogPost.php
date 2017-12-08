<div class="content">
    <div class="container">
        <div class="divLogo-min text-center">
            <img class="logoBody" src="/img/logo3minAlt.png" alt="logo"><br />
            <span class="blogPostCat"><?= $blogPost['categorie'] ?></span>
        </div>
        <div class="text-center">
        <?php
        if($user->hasMessage() && ($_SESSION['trajet'] === 'redirect'))
        {
            echo '<p class="message">', $user->getMessage(), '</p>';
            $_SESSION['trajet'] = 'direct';
        }
        ?>
        </div>

        <?php if (isset($image['serverFile']))
        {
            ?>
            <div class="row">
                <div class="img-blogPost col-md-offset-2 col-md-8">
                    <img class="img-responsive full-border" src="/imgUp/<?= $image['serverFile'] ?>" alt="illustration de l'article">
                </div>
            </div>
            <?php
        }
        ?>

        <div class="blogPost">
            <h2 class="titre text-center marginTop"><?= htmlspecialchars($blogPost['titre'])?></h2>
            <p class="date">Mis à jour le <?= $blogPost['dateModif']->format('d/m/Y à H\hi')?></p>

            <p class="chapo"><?= nl2br(htmlspecialchars($blogPost['chapo']))?></p>
            <p class="contenu"><?= nl2br(htmlspecialchars($blogPost['contenu']))?></p>
            <p class="contenu">Article rédigé par <span class="auteurBlogPost"><?= htmlspecialchars($blogPost['auteur'])?></span></p>
        </div>
        <div class="action text-right">
            <a type="button" class="btn modifier btn-sm" href="/blogPost/update/<?= $blogPost['id']?>.html">Modifier</a>
            <a type="button" class="btn supprimer btn-sm" href="/blogPost/delete/<?= $blogPost['id']?>.html">Supprimer</a>
        </div>
    </div>

        <img class="full-img border" src="/img/tree-slice4.jpg">

    <div class="container">
        <div class="commentaires">
            <div class="titreCommentaires">
                <p class="text-center"><a type="button" class="btn envoyer btn-md" href="/../comment/insert/<?= $blogPost['id']?>.html">Ajouter un commentaire</a></p>
            </div>
            <?php
            foreach ($commentList as $comment)
            {
                ?>
                <div class="commentaire">
                    <p>par <span class="auteurCommentaire"><?= htmlspecialchars($comment->auteur())?></span> <span class="dateCommentaire">le <?= $comment->dateModif()->format('d/m/Y à H\hi')?></span>
                    </p>

                    <p class="contenuCommentaire"><?= nl2br(htmlspecialchars($comment->contenu()))?></p>
                    <div class="action-com text-right">
                            <a type="button" class="btn modifier btn-sm" href="/../comment/update/<?=$comment['id']?>.html">Modifier</a>
                            <a type="button" class="btn supprimer btn-sm" href="/../comment/delete/<?=$comment['id']?>.html">Supprimer</a>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="row pagination">
                <?php
                $numeroPage = 1;

                for ($i = 1; $i <= $nbCommentaires; $i += $commentsPerPage) {
                    ?>
                    <a class="btn btn-info" href="/blogPost/<?= $blogPost['id'] ?>/p<?= $numeroPage ?>.html"><?= $numeroPage?></a>
                    <?php $numeroPage++;
                }
                ?>
            </div>
        </div>
    </div>
</div>