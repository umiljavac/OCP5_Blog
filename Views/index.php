<section>
    <div class="container">
        <div class="divLogo text-center">
            <img class="logoBody" src="img/logo3min.png" alt="logo">
        </div>

        <?php  if($user->hasMessage() && ($_SESSION['trajet'] == 'redirect')) {
            echo '<p>', $user->getMessage(), '</p>';
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
              <h3 class="text-center"><a class="titreBlogPostList" href="blogPost/<?=$blogPost->id()?>"><?= htmlspecialchars($blogPost->titre()) ?></a></h3>
                <p class="chapoBlogPostList"><?= nl2br(htmlspecialchars($blogPost->chapo())) ?></p>
              <p class="date"><?='le '.$blogPost->dateAjout()->format('d/m/Y à H\hi')?></p>
            </div>
        </div>
        <?php
    }
    ?>
    </div>
</section>