<!-- Header -->
<header>
   <!-- <div class="container" id="ulm">
        <div class="row">
            <div class="col-lg-offset-3 col-lg-6">
                <img class="img-responsive border full-img" src="img/baby.jpg" alt="Baby, le premier ordinateur à programme enregistré">
                <div class="intro-text">
                    <span class="name">Ulrich Miljavac</span>
                    <hr class="star-primary">
                    <span class="skills">J'utilise HTML pour le dire, CSS pour y mettre la forme, Javascript pour ouvrir des perspectives, PHP pour dialoguer avec le fond des choses, SQL pour ne rien oublier </span>
                </div>
            </div>
        </div>
    </div> -->
    <img class="full-img" src="img/tree.jpg">
    <img class="full-img carte" src="img/carte1.png">
     <div class="container" id="ulm">
        <div class="row">
            <div class="col-lg-offset-3 col-lg-6">
                <div class="intro-text">
                    <span class="name">Ulrich Miljavac</span>
                    <div class="divLogo">
                        <img class="text-center logoBody" src="img/logo3min.png">
                    </div>
                    <span class="skills">J'utilise HTML pour le dire, CSS pour y mettre la forme, Javascript pour ouvrir des perspectives, PHP pour dialoguer avec le fond des choses, SQL pour ne rien oublier. </span>
                </div>
            </div>
        </div>
    </div>

</header>

<!-- About Section -->
<section class="success" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>A propos</h2>
                <hr class="star-light">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-2">
                <p>Freelancer is a free bootstrap theme created by Start Bootstrap. The download includes the complete source files including HTML, CSS, and JavaScript as well as optional LESS stylesheets for easy customization.</p>
            </div>
            <div class="col-lg-4">
                <p>Whether you're a student looking to showcase your work, a professional looking to attract clients, or a graphic artist looking to share your projects, this template is the perfect starting point!</p>
            </div>
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <a href="#" class="btn btn-lg btn-outline">
                    <i class="fa fa-download"></i> Download Theme
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>Contact</h2>
                <hr class="star-primary">
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

