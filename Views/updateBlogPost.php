<div class="container marginTop">
    <div class="row">
        <form class="col-md-offset-2 col-md-8 col-sm-12" action="" method="post">
            <legend>Modifier le blogpost :
                <h3><a href="/blogPost/<?= $blogPost['id'] ?>"><?= htmlspecialchars($blogPost['titre']) ?></a></h3>
                rédigé par <i><?= htmlspecialchars($blogPost['auteur']) ?></i>
            </legend>
            <div class="form-group">
                <label for="auteur">Auteur : </label>
                <input id="auteur" type="text" name="auteur" class="form-control" value="<?= htmlspecialchars($blogPost['auteur']) ?>">
            </div>
            <div class="form-group">
                <label for="titre">Titre : </label>
                <input id="titre" name="titre" type="text" class="form-control" value="<?= htmlspecialchars($blogPost['titre']) ?>">
            </div>
            <div class="form-group">
                <label for="chapo">Introduction, mise en bouche ou chapô : </label>
                <textarea id="chapo" type="textarea" name="chapo" class="form-control"><?= htmlspecialchars($blogPost['chapo']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="contenu">Contenu : </label>
                <textarea id="contenu" type="textarea" name="contenu" class="form-control"><?= htmlspecialchars($blogPost['contenu']) ?></textarea>
            </div>
            <button class="btn btn-info" type="submit">Modifier</button>
        </form>
    </div>
</div>