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
        $this->config = new Config;
        $this->user = new User;
        $this->page = new Page;
        $this->visitorRequest = $_SERVER['REQUEST_URI'];
        $this->getMatchedRoute();
        $this->execute();
        $this->send();
    }

    public function getMatchedRoute()
    {
        $xml = new \DOMDocument();
        $xml->load(__DIR__.'/../../Config/routes.xml');
        $xmlRoutes = $xml->getElementsByTagName('route');

        foreach ($xmlRoutes as $route)
        {
            $vars = [];

            if ($route->hasAttribute('vars'))
            {
                $vars =  explode(',', $route->getAttribute('vars'));
            }

            $this->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('view'), $vars));
        }

        foreach ($this->routes as $route) {

                if (($varsValues = $route->matchUrl($this->visitorRequest())) !== false)
                {
                    if ($route->hasVar()) {
                        $varsNames = $route->varsNames();
                        $listVars = [];
                        foreach ($varsValues as $key => $value)
                        {
                            if ($key !== 0)
                            {
                                $listVars[$varsNames[$key-1]] = $value;
                            }
                        }
                        $route->setVars($listVars);
                    }

                    $this->setRoute($route);
                    $this->setActionView($this->route()->view());
                    $_GET = array_merge($_GET, $route->vars());

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
        $blogPostList = $blogPostManager->getList($this->config->getConfig('blogPosts'), $this->getData('page'));
        $nbBlogPost = $blogPostManager->count();
        $this->page->addVar('blogPostList', $blogPostList);
        $this->page->addVar('nbBlogPost', $nbBlogPost);
    }

    public function executeShowBlogPost()
    {
        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($this->getData('id'));
        $this->page->addVar('blogPost', $blogPost);

        $commentManager = new CommentManager;
        $commentList = $commentManager->getList($this->getData('id'), $this->config->getConfig('comments'), $this->getData('page'));
        $nbCommentaires = $commentManager->count($this->getData('id'));
        $this->page->addVar('commentList', $commentList);
        $this->page->addVar('nbCommentaires', $nbCommentaires);
    }

    public function postData($key)
    {
        return isset($_POST[$key])? $_POST[$key] : null;
    }

    public function getData($key)
    {
        return isset($_GET[$key])? $_GET[$key] : null;
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

                $this->redirect('/index/1');
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
        $blogPost = $blogPostManager->getUnique($this->getData('id'));
        $this->page->addVar('blogPost', $blogPost);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $blogPost = new BlogPost([
                'titre' => $this->postData('titre'),
                'auteur' => $this->postData('auteur'),
                'chapo' => $this->postData('chapo'),
                'contenu' => $this->postData('contenu'),
                'id' => $this->getData('id')
            ]);

            if ($blogPost->isValid())
            {
                $blogPostManager->update($blogPost);

                $this->user->setMessage('Le blogpost a bien été modifié');

                $this->redirect('/blogPost/'. $blogPost->id() . '/page/1');
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
        $blogPostManager->delete($this->getData('id'));

        $this->user->setMessage('Le blogpost a bien été supprimé');

        $this->redirect('/index/1');
    }

    public function executeInsertComment()
    {
        $this->executeShowBlogPost(); // pour récupérer le blogpost dans la vue de création d'un commentaire

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $comment = new Comment([
                'blogPost' => $this->getData('blogPost'),
                'auteur' => $this->postData('auteur'),
                'contenu' => $this->postData('contenu')
            ]);

            if ($comment->isValid())
            {

                $commentManager = new CommentManager;
                $commentManager->insert($comment);

                $this->user->setMessage('Votre commentaire a bien été ajouté');
                $this->user->setAttribute('auteur', $comment->auteur());

                $this->redirect('/blogPost/'. $this->getData('blogPost') . '/page/1');
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
        $comment = $commentManager->getUnique($this->getData('id'));
        $blogPostId = $comment->blogPost();
        $this->page->addVar('comment', $comment);

        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($blogPostId);
        $this->page->addVar('blogPost', $blogPost);


        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $comment = new Comment([
               'id' => $this->getData('id'),
               'blogPost' => $blogPostId,
               'auteur' => $this->postData('auteur'),
               'contenu' => $this->postData('contenu')
            ]);

            if ($comment->isValid())
            {
                $commentManager->update($comment);

                $this->user->setMessage('Le commentaire a bien été modifié');

                $this->redirect('/blogPost/'. $blogPostId . '/page/1');
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
        $blogPost = $commentManager->delete($this->getData('id'));

        $this->user->setMessage('Le commentaire a bien été supprimé');

        $this->redirect('/blogPost/'. $blogPost . '/page/1');
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
        $config = $this->config();
        $user = $this->user();

        extract($this->page->vars());

        ob_start();
        require $this->page->fileView();
        $content = ob_get_clean();

        ob_start();
        require __DIR__.'/../../Templates/layout.php';
        exit(ob_get_clean());
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

        $this->page->setFileView(__DIR__.'/../../Views/'.$actionView.'.php');
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

    public function config()
    {
        return $this->config;
    }

}