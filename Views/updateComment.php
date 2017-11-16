<div class="container marginTop">
    <div class="row">
        <div class="divLogo text-center">
            <img class="logoBody" src="/img/logo3min.png">
        </div>
        <form class="col-md-offset-2 col-md-8 col-sm-12" action="" method="post">
            <legend>Modifier le commentaire du blogPost :
                <h3><a href="/blogPost/<?= $blogPost['id'] ?>/p1"><?= htmlspecialchars($blogPost['titre']) ?></a></h3>
                rédigé par <i><?= htmlspecialchars($blogPost['auteur']) ?></i>
            </legend>
            <div class="form-group">
                <label for="auteur">* Auteur : </label>
                <input id="auteur" type="text" name="auteur" class="form-control" value="<?= $comment['auteur'] ?>">
            </div>
            <div class="form-group">
                <label for="contenu">* Contenu : </label>
                <textarea id="contenu" type="textarea" name="contenu" class="form-control"><?= $comment['contenu'] ?></textarea>
            </div>
            <button class="btn envoyer" type="submit">Modifier</button>
            <p class="asterisque">* l'astérisque à horreur du vide</p>
        </form>
    </div>
</div>