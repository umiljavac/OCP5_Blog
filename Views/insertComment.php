<div class="container marginTop">
    <div class="row">
        <div class="divLogo">
            <img class="logoBody" src="/img/logo3min.png">
        </div>
        <?php if(($_SERVER['REQUEST_METHOD'] == 'POST') && $user->hasMessage()) echo '<p class="col-md-offset-2 col-md-8 col-sm-12 warning">',$user->getMessage(),'</p>'; ?>
        <form class="col-md-offset-2 col-md-8 col-sm-12" action="" method="post">
            <legend>Ajouter un commentaire au blogpost :
                <h3><a href="/blogPost/<?= $blogPost['id'] ?>/p1"><?= htmlspecialchars($blogPost['titre']) ?></a></h3>
                rédigé par <i><?= htmlspecialchars($blogPost['auteur']) ?></i>
            </legend>
            <div class="form-group">
                <label for="auteur">Auteur : </label>
                <input id="auteur" type="text" name="auteur" value="<?= $user->getAttribute('auteur') ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="contenu">Contenu du commentaire : </label>
                <textarea id="contenu" rows="5" name="contenu" type="textarea" class="form-control"><?php if (isset($_POST['contenu'])) echo $_POST['contenu']?></textarea>
            </div>
            <button class="btn envoyer" type="submit">Envoyer</button>
        </form>
    </div>
</div>