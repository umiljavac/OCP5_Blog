<div class="container marginTop">
    <div class="row">
        <div class="divLogo text-center">
            <img class="logoBody" src="/img/logo3min.png">
        </div>
        <?php if(($_SERVER['REQUEST_METHOD'] == 'POST') && $user->hasMessage()) echo '<p class="col-md-offset-2 col-md-8 col-sm-12 warning">', $user->getMessage(),'</p>'; ?>
        <form class="col-md-offset-2 col-md-8 col-sm-12" action="" method="post" enctype="multipart/form-data">
            <legend>Modifier le blogpost :
                <h3><a href="/blogPost/<?= $blogPost['id'] ?>/p1"><?= htmlspecialchars($blogPost['titre']) ?></a></h3>
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
                <textarea id="contenu" type="textarea" rows="10" name="contenu" class="form-control"><?= htmlspecialchars($blogPost['contenu']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="categorie">Catégorie : </label>
                <select id="categorie" name="categorie" class="form-control">
                    <option value="actu">Actu</option>
                    <option value="litterature">Littérature</option>
                    <option value="musique">Musique</option>
                    <option value="programmation">Programmation</option>
                    <option value="science">Science</option>
                    <option value="societe">Société</option>
                </select>
            </div>
            <div class="form-group">
                <input type="hidden" name="MAX_FILE_SIZE" value="<?= $tailleMax ?>">
                <?php if(isset($actualImage['serverFile']))
                {?>
                    <img class="img-responsive" src="/imgUp/<?= $actualImage['serverFile'] ?>"> <br />
                    <label for="image">Mettre à jour la photo d'illustration (jpg, jpeg, png, 2 Mo max) : </label>
                    <?php
                } else { ?>
                    <label for="image">Ajouter une photo d'illustration (jpg, jpeg, png, 2 Mo max) : </label>
                    <?php
                }
                ?>
                <input type="file" name="image">
            </div>
            <button class="btn envoyer" type="submit">Modifier</button>
        </form>
    </div>
</div>