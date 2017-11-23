<div class="container marginTop">
    <div class="row">
        <div class="divLogo text-center">
            <img class="logoBody" src="/img/logo3min.png">
        </div>
        <?php if(($_SERVER['REQUEST_METHOD'] === 'POST') && $user->hasMessage()) echo '<p class="col-md-offset-2 col-md-8 col-sm-12 warning">', $user->getMessage(),'</p>'; ?>
        <form class="col-md-offset-2 col-md-8 col-sm-12" action="" method="post" enctype="multipart/form-data">
            <legend>Ajouter un blogpost</legend>
            <div class="form-group">
                <label for="auteur">* Auteur : </label>
                <input id="auteur" type="text" name="auteur" value="<?= $user->getAttribute('auteur') ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="titre">* Titre : </label>
                <input id="titre" name="titre" type="text"  value="<?php if (isset($_POST['titre'])) echo $_POST['titre'] ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="chapo">* Introduction, mise en bouche ou chapô : </label>
                <textarea id="chapo" type="textarea" name="chapo" class="form-control"><?php if (isset($_POST['chapo'])) echo $_POST['chapo'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="contenu">* Contenu : </label>
                <textarea id="contenu" rows="10" type="textarea" name="contenu" class="form-control"><?php if (isset($_POST['contenu'])) echo $_POST['contenu'] ?></textarea>
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
                <label for="image">Ajouter une photo d'illustration (jpg, jpeg, png, 2 Mo max) :</label>
                <input type="file" name="image">
            </div>
            <button class="btn envoyer" type="submit">Ajouter</button>
            <p class="asterisque">* l'astérisque a horreur du vide</p>
        </form>
    </div>
</div>