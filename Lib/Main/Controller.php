<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 10:13
 */
namespace Main;

use Entity\BlogPost;
use Entity\Comment;
use Model\BlogPostManager;
use Model\CommentManager;

class Controller
{
    protected $visitorRequest;
    protected $route;
    protected $routes = [];
    protected $actionView;
    protected $page = null;
    protected $user = null;

    public function run()
    {
        $this->user = new User;
        $this->page = new Page;
        $this->visitorRequest = $_SERVER['REQUEST_URI'];
        $this->getMatchedRoute();
        $this->execute();
        $this->send();
    }

    public function getMatchedRoute()
    {
        // je charge le tableau des routes avec les routes du fichier xml
        $xml = new \DOMDocument();
        $xml->load(__DIR__.'/../../Config/routes.xml');
        $xmlRoutes = $xml->getElementsByTagName('route');

        foreach ($xmlRoutes as $route)
        {
            $varName = '';

            if ($route->hasAttribute('var'))
            {
                $varName = $route->getAttribute('var');
            }

            $this->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('view'), $varName));
        }

        // je cherche la bonne route dans le tableau

        foreach ($this->routes as $route) {

                if (($matchedRoute = $route->matchUrl($this->visitorRequest())) !== false) // si la route correspond à l'URL
                {
                    if ($route->hasVar()) {
                        $matchedValue = $matchedRoute[1]; // voir doc preg_match
                        $route->setVarValue($matchedValue);
                    }

                    $this->setRoute($route);
                    $this->setActionView($this->route()->view());
                    return;
                }
            }
        $this->redirect404();

        throw new \RuntimeException('Aucune route ne correspond à l\'URL');
    }

    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes))
        {
            $this->routes[] = $route;
        }
    }

    public function execute()
    {
        $actionRequest = 'execute'.ucfirst($this->actionView());
        if (!is_callable([$this, $actionRequest]))
        {
            throw new \RuntimeException('L\'action demandée est impossible');
        }

        $this->$actionRequest();
    }

    public function executeAccueil()
    {
    }

    public function executeIndex()
    {
        $blogPostManager = new BlogPostManager;
        $blogPostList = $blogPostManager->getList();
        $this->page->addVar('blogPostList', $blogPostList);
    }

    public function executeShowBlogPost()
    {
        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($this->route()->varValue());
        $this->page->addVar('blogPost', $blogPost);

        $commentManager = new CommentManager;
        $commentList = $commentManager->getList($this->route()->varValue());
        $this->page->addVar('commentList', $commentList);
    }

    public function postData($key)
    {
        return isset($_POST[$key])? $_POST[$key] : null;
    }

    public function executeInsertBlogPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $blogPost = new BlogPost([
                'titre' => $this->postData('titre'),
                'auteur' => $this->postData('auteur'),
                'chapo' => $this->postData('chapo'),
                'contenu' => $this->postData('contenu')
            ]);

            if ($blogPost->isValid())
            {
                $blogPostManager = new BlogPostManager;
                $blogPostManager->insert($blogPost);

                $this->user->setMessage('Votre blogpost a bien été ajouté');
                $this->user->setAttribute('auteur', $blogPost->auteur());

                $this->redirect('/index');
            }
            else
            {
                $this->returnFormError($blogPost);
            }
        }
        else
        {
            return;
        }
    }

    public function executeUpdateBlogPost()
    {
        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($this->route()->varValue());
        $this->page->addVar('blogPost', $blogPost);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $blogPost = new BlogPost([
                'titre' => $_POST['titre'],
                'auteur' => $_POST['auteur'],
                'chapo' => $_POST['chapo'],
                'contenu' => $_POST['contenu'],
                'id' => $this->route()->varValue()
            ]);

            if ($blogPost->isValid())
            {
                $blogPostManager->update($blogPost);

                $this->user->setMessage('Le blogpost a bien été modifié');

                $this->redirect('/blogPost/'. $blogPost->id());
            }
        }
        else
        {
            return;
        }
    }

    public function executeDeleteBlogPost()
    {
        $blogPostManager = new BlogPostManager();
        $blogPostManager->delete($this->route->varValue());

        $this->user->setMessage('Le blogpost a bien été supprimé');

        $this->redirect('/index');
    }

    public function executeInsertComment()
    {
        $this->executeShowBlogPost(); // pour récupérer le blogpost dans la vue de création d'un commentaire

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $comment = new Comment([
                'blogPost' => $this->route->varValue(),
                'auteur' => $_POST['auteur'],
                'contenu' => $_POST['contenu']
            ]);

            if ($comment->isValid())
            {

                $commentManager = new CommentManager;
                $commentManager->insert($comment);

                $this->user->setMessage('Votre commentaire a bien été ajouté');
                $this->user->setAttribute('auteur', $comment->auteur());

                $this->redirect('/blogPost/'. $comment->blogPost());
            }
            else
            {
               $this->returnFormError($comment);
            }
        }
        else
        {
            return;
        }
    }

    public function executeUpdateComment()
    {
        $commentManager = new commentManager;
        $comment = $commentManager->getUnique($this->route()->varValue());
        $blogPostId = $comment->blogPost();
        $this->page->addVar('comment', $comment);

        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($blogPostId);
        $this->page->addVar('blogPost', $blogPost);


        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $comment = new Comment([
               'id' => $this->route()->varValue(),
               'blogPost' => $blogPostId,
               'auteur' => $_POST['auteur'],
               'contenu' => $_POST['contenu']
            ]);

            if ($comment->isValid())
            {
                $commentManager->update($comment);

                $this->user->setMessage('Le commentaire a bien été modifié');

                $this->redirect('/blogPost/'. $blogPostId);
            }
        }
        else
        {
            return;
        }
    }

    public function executeDeleteComment()
    {
        $commentManager = new CommentManager;
        $blogPost = $commentManager->delete($this->route->varValue());

        $this->user->setMessage('Le commentaire a bien été supprimé');

        $this->redirect('/blogPost/'. $blogPost);
    }

    public function returnFormError(Entity $entity) {
        // boucle de récupération des erreurs
        $erreurString = '';
        foreach ($entity->erreurs() as $erreur)
        {
            $erreurString .= ' - ' .$erreur . '<br />';
        }
        $this->user->setMessage('Merci de remplir tous les champs du formulaire <br />' . $erreurString);
    }

    public function redirect($redirection)
    {
        $_SESSION['trajet'] = 'redirect';
        header('Location: '. $redirection);
    }

    public function redirect404()
    {
        $this->page->setFileView(__DIR__. '/../../Errors/404.html');
        $this->send();
    }

    public function setCookie($name, $value ='', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public function send()
    {
        $user = $this->user();
        extract($this->page->vars()); /* normalement cette manip permet d'avoir accés aux variables contenues dans le tableau vars[] qui
         est un attribut de la classe Page : on a accés à tout ce qu'à renvoyé le manager (auteur du blogPost ou du comment, titre du Blogpost
        chapo du blogPost, contenu du blogPost ou du comment, date de modif, ajout .. ) */
        ob_start(); // débute la temporisation de sortie : ob = Out Buffer .. ça veut dire que ça va repartir chez le visiteur :)
        require $this->page->fileView(); // on charge et exige que le fichier de la vue liée à la page donc à la demande du visiteur
        //  soit présent dans le tampon
        // (ça fait par exemple pour l'index de tous les blogPost : require 'index.php' dans le tampon )
        $content = ob_get_clean(); // là on met tout le fichier de la vue dans la variable $content :) dont on va se servir dans le layout;)
        // mais c'est pas finit on a pas retourné quoi que ce soit ..

        ob_start(); // on recommence une temporisation
        require __DIR__.'/../../Templates/layout.php'; // on charge le layout
        exit(ob_get_clean()); // et là on balance tout, on renvoit la réponse du visiteur ! c'est bien ça ?:)
    }

    // SETTERS

    public function setRoute(Route $route)
    {
        $this->route = $route;
    }

    public function setActionView($actionView)
    {
        if (!is_string($actionView) || empty($actionView))
        {
            throw new \InvalidArgumentException('La vue doit être une chaîne de caractère valide');
        }

        $this->actionView = $actionView;

        $this->page->setFileView(__DIR__.'/../../Views/'.$actionView.'.php'); // là j'inclus le fichier de la vue dans l'instance de ma classe Page
    }

    // GETTERS

    public function actionView()
    {
        return $this->actionView;
    }

    public function page()
    {
        return $this->page;
    }

    public function route()
    {
        return $this->route;
    }

    public function visitorRequest()
    {
        return $this->visitorRequest;
    }

    public function user()
    {
        return $this->user;
    }

}