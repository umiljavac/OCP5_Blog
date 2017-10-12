<!-- Header -->
<header>
    <img class="full-img" src="img/photo-accueil.png">
     <div class="container" id="ulm">
        <div class="row">
            <div class="col-lg-offset-3 col-lg-6">
                <div class="intro-text">
                    <span class="name">Ulrich Miljavac</span>
                    <div class="divLogo">
                        <img class="text-center logoBody" src="img/logo3min.png" alt="logo">
                    </div>
                    <span class="skills">J'utilise HTML pour le dire, CSS pour y mettre la forme, Javascript pour ouvrir des perspectives, PHP pour dialoguer avec le fond des choses, SQL pour ne rien oublier. </span>
                </div>
            </div>
        </div>
    </div>

</header>

<img class="full-img border" src="img/tree-slice4.jpg">

<!-- About Section -->
<section class="success" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="about">A propos</h2>
                <div class="divLogo">
                    <img class="logoBody" src="img/logo3min.png">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-2">
                <p class="reduce">Ce blog est le résultat d'une volonté personnelle de reconversion professionnelle. Cette volonté est née d'une envie simple : allier nécessité et passion, travailler en aimant son travail. Pour y parvenir, je me suis inscrit à la formation <a style="color: black; font-weight: bold" href="https://openclassrooms.com/paths/developpeur-se-d-application-php-symfony">" Développeur d'application PHP/Symfony "</a> chez OpenClassrooms. C'est une formation qui demande beaucoup d'autonomie, un esprit ouvert et une motivation sans faille.</p>
            </div>
            <div class="col-lg-4">
                <p class="reduce">Sur 9 projets à réaliser durant la formation, ce blog est le cinquième et représente une mise en pratique concrète de mes connaissances actuelles dans le développement web. Il nous est demandé de créer un blog sans utiliser de librairies/frameworks externes. Chaque visiteur peut créer, modifier, supprimer du contenu. Ici, tout est donc accessible et ouvert : Entrez Libres </p>
            </div>
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <a href="#" class="btn btn-lg btn-outline">
                    <i class="fa fa-download"></i> Download Theme
                </a>
            </div>
        </div>
    </div>
</section>

<img class="full-img border" src="img/frise-slice3.png">
<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="about">Contact</h2>
                <div class="divLogo">
                    <img class="logoBody" src="img/logo3min.png">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                <form name="sentMessage" id="contactForm" novalidate>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Nom ou Pseudo</label>
                            <input type="text" class="form-control" placeholder="Nom ou pseudo" id="name" required data-validation-required-message="Veuillez renseigner votre nom ou votre pseudo.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Adresse mail</label>
                            <input type="email" class="form-control" placeholder="Adresse email" id="email" required data-validation-required-message="Veuillez renseigner votre adresse email.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Telephone</label>
                            <input type="tel" class="form-control" placeholder="Telephone" id="phone" required data-validation-required-message="Veuillez renseigner votre numéro de télephone.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label>Message</label>
                            <textarea rows="5" class="form-control" placeholder="Message" id="message" required data-validation-required-message="Veuillez ajouter un message."></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <br>
                    <div id="success"></div>
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <button type="submit" class="btn envoyer btn-lg">Envoyer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

