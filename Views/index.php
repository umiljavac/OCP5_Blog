<div class="fondIndex content">
    <div class="container">

        <?php  if($user->hasMessage() && ($_SESSION['trajet'] == 'redirect')) {
            echo '<p class="message">', $user->getMessage(), '</p>';
            $_SESSION['trajet'] = 'direct';
        } else {
            $_SESSION['trajet'] = null;
        }
        ?>
    <?php
    foreach ($blogPostList as $blogPost)
    {
        ?>
        <div class="row">
            <div class="blogPostList">
              <h3 class="text-center"><a class="titreBlogPostList" href="/blogPost/<?=$blogPost->id()?>/page/1"><?= htmlspecialchars($blogPost->titre()) ?></a></h3>
                <p class="chapoBlogPostList"><?= nl2br(htmlspecialchars($blogPost->chapo())) ?></p>
              <p class="dateBlogPostList"><?='le '.$blogPost->dateAjout()->format('d/m/Y à H\hi')?></p>
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
            <a href="/index/<?= $numeroPage ?>"><button class="btn-page"><?= $numeroPage?></button></a>
        <?php $numeroPage++;
        }
        ?>
        </div>
    </div>
</div