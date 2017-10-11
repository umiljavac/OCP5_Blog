<div class="container marginTop">
    <div class="row">
        <?php if(($_SERVER['REQUEST_METHOD'] == 'POST') && $user->hasMessage()) echo '<p class="col-md-offset-2 col-md-8 col-sm-12 warning">',$user->getMessage(),'</p>'; ?>
        <form class="col-md-offset-2 col-md-8 col-sm-12" action="" method="post">
            <legend>Ajouter un blogpost</legend>
            <div class="form-group">
                <label for="auteur">Auteur : </label>
                <input id="auteur" type="text" name="auteur" value="<?= $user->getAttribute('auteur') ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="titre">Titre : </label>
                <input id="titre" name="titre" type="text"  value="<?php if (isset($_POST['titre'])) echo $_POST['titre'] ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="chapo">Introduction, mise en bouche ou chapô : </label>
                <textarea id="chapo" type="textarea" name="chapo" class="form-control"><?php if (isset($_POST['chapo'])) echo $_POST['chapo'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="contenu">Contenu : </label>
                <textarea id="contenu" rows="10" type="textarea" name="contenu" class="form-control"><?php if (isset($_POST['contenu'])) echo $_POST['contenu'] ?></textarea>
            </div>
            <button class="btn ajouter" type="submit">Envoyer</button>
        </form>
    </div>
</div>