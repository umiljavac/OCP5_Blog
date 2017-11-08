<div class="fondIndex content">
    <div class="container">
        <div class="text-center">
        <?php  if($user->hasMessage() && ($_SESSION['trajet'] == 'redirect')) {
            echo '<p class="message">', $user->getMessage(), '</p>';
            $_SESSION['trajet'] = 'direct';
        } else {
            $_SESSION['trajet'] = null;
        }
        ?>
        </div>
        <div class="row">
            <div class="text-center">
                <div class="dropdown btn-group">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Filtrer par catégorie
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="/index/p1/cat/actu">Actu</a></li>
                        <li><a href="/index/p1/cat/litterature">Littérature</a></li>
                        <li><a href="/index/p1/cat/musique">Musique</a></li>
                        <li><a href="/index/p1/cat/programmation">Programmation</a></li>
                        <li><a href="/index/p1/cat/science">Science</a></li>
                        <li><a href="/index/p1/cat/societe">Société</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/index/p1/cat/all">Toutes..</a></li>
                    </ul>
                </div>
            </div>
        </div>
    <?php
    foreach ($blogPostList as $blogPost)
    {
        ?>
        <div class="blogPostList">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12 div-img-index" >
                    <?php
                    foreach ($imageList as $image)
                    {
                        if ($blogPost['id'] === $image['blogPostId'])
                        {

                            ?> <img class="img-responsive img-index center-block" src="/imgUp/<?= $image['serverFile'] ?>">
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="col-lg-8 col-md-6">
                    <h3 class="text-center h3BlogPostList"><a class="titreBlogPostList" href="/blogPost/<?=$blogPost->id()?>/p1"><?= htmlspecialchars($blogPost->titre()) ?></a></h3>
                    <p class="chapoBlogPostList"><?= nl2br(htmlspecialchars($blogPost->chapo())) ?></p>
                </div>
                <div class="col-lg-2 col-md-3 bp-info">
                    <div class="col-sm-12 cat text-center ">
                        <span><?= $blogPost['categorie'] ?></span>
                    </div>
                    <div class="bp-date col-sm-12 text-center">
                        <p class="dateBlogPostList "><?='le '.$blogPost->dateAjout()->format('d/m/Y à H\hi')?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
        <div class="row pagination">
        <?php
        $numeroPage = 1;
        for ($i = 1; $i <= $nbBlogPost; $i += $config->getConfig('blogPosts')) {
        ?>
            <a class="btn btn-info" href="/index/p<?= $numeroPage ?>/cat/<?= $categorie ?>"><?= $numeroPage ?></a>
        <?php $numeroPage++;
        }
        ?>
        </div>
    </div>
</div