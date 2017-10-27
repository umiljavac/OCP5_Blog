<div class="content">

    <img class="full-img border" src="/img/frise-slice3.png">

    <div class="container">
        <div class="divLogo text-center">
            <img class="logoBody" src="/img/logo3min.png" alt="logo">
        </div>
        <div class="text-center">
        <?php
        if($user->hasMessage() && ($_SESSION['trajet'] == 'redirect'))
        {
            echo '<p class="message">', $user->getMessage(), '</p>';
            $_SESSION['trajet'] = 'direct';
        }
        ?>
        </div>
        <div class="blogPost">
            <h2 class="titre text-center marginTop"><?= htmlspecialchars($blogPost['titre'])?></h2>
            <p class="date">Mis à jour le <?= $blogPost['dateModif']->format('d/m/Y à H\hi')?></p>

            <p class="chapo"><?= nl2br(htmlspecialchars($blogPost['chapo']))?></p>
            <p class="contenu"><?= nl2br(htmlspecialchars($blogPost['contenu']))?></p>
            <p class="contenu">Article rédigé par <span class="auteurBlogPost"><?= htmlspecialchars($blogPost['auteur'])?></span></p>
            <div class="action text-right">
                <a type="button" class="btn modifier btn-sm" href="/blogPost/update/<?= $blogPost['id']?>">Modifier</a>
                <a type="button" class="btn supprimer btn-sm" href="/blogPost/delete/<?= $blogPost['id']?>">Supprimer</a>
            </div>
        </div>
    </div>

        <img class="full-img border" src="/img/tree-slice4.jpg">

    <div class="container">
        <div class="commentaires">
            <div class="titreCommentaires">
                <p class="text-center"><a type="button" class="btn envoyer btn-md" href="/../comment/insert/<?= $blogPost['id']?>">Ajouter un commentaire</a></p>
            </div>
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
            <div class="row pagination">
                <?php
                $numeroPage = 1;

                for ($i = 1; $i <= $nbCommentaires; $i += $config->getConfig('comments')) {
                    ?>
                    <a class="btn btn-info" href="/blogPost/<?= $blogPost['id'] ?>/p<?= $numeroPage ?>"><?= $numeroPage?></a>
                    <?php $numeroPage++;
                }
                ?>
            </div>
        </div>
    </div>
</div>