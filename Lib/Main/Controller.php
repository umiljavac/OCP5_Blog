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
use Entity\Image;
use Model\BlogPostManager;
use Model\CommentManager;
use Model\ImageManager;

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
        $categorie = $this->getData('cat');

        $blogPostManager = new BlogPostManager;
        $blogPostList = $blogPostManager->getList($categorie, $this->config->getConfig('blogPosts'), $this->getData('page'));
        $nbBlogPost = $blogPostManager->count($categorie);

        $imageManager = new ImageManager;
        $imageList = $imageManager->getList($categorie, $this->config->getConfig('blogPosts'), $this->getData('page'));

        $this->page->addVars(['blogPostList' => $blogPostList, 'nbBlogPost' => $nbBlogPost, 'categorie' => $categorie, 'imageList' => $imageList]);
    }

    public function executeShowBlogPost()
    {
        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($this->getData('id'));

        $imageManager = new ImageManager;
        $image = $imageManager->getUnique($this->getData('id'));

        $commentManager = new CommentManager;
        $commentList = $commentManager->getList($this->getData('id'), $this->config->getConfig('comments'), $this->getData('page'));
        $nbCommentaires = $commentManager->count($this->getData('id'));

        $this->page->addVars(['blogPost' => $blogPost, 'image' => $image, 'commentList' => $commentList, 'nbCommentaires' => $nbCommentaires]);

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
        $this->page->addVars(['tailleMax' => Image::MAX_SIZE]);

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $blogPostManager = new BlogPostManager;

            $blogPost = new BlogPost([
                'titre' => $this->postData('titre'),
                'auteur' => $this->postData('auteur'),
                'chapo' => $this->postData('chapo'),
                'contenu' => $this->postData('contenu'),
                'categorie' => $this->postData('categorie')
            ]);

            $image = new Image;

            if ($blogPost->isValid())
            {
                if ($image->tryUpload())
                {
                    if ($image->isValid())
                    {
                        $blogPostManager->insert($blogPost);
                        $blogPostId = $blogPostManager->lastInsertId();
                        $image->setBlogPostId($blogPostId);
                        $this->executeInsertImage($image);

                        $this->user->setMessage('Votre blogpost a bien été ajouté avec une illustration');
                        $this->user->setAttribute('auteur', $blogPost->auteur());

                        $this->redirect('/index/p1/cat/all');
                    }
                    else
                    {
                        $this->returnFormError($image);
                    }
                }
                else
                {
                    $blogPostManager->insert($blogPost);

                    $this->user->setMessage('Votre blogpost a bien été ajouté sans illustration');
                    $this->user->setAttribute('auteur', $blogPost->auteur());
                    $this->redirect('/index/p1/cat/all');
                }
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

    public function executeInsertImage(Image $image)
    {
        move_uploaded_file($_FILES[$image::UPLOAD]['tmp_name'], $image::IMG_DIR . $image->serverFile());

        $imageManager = new ImageManager;
        $imageManager->insert($image);
    }

    public function executeUpdateBlogPost()
    {
        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($this->getData('id'));

        $imageManager = new ImageManager;
        $actualImage = $imageManager->getUnique($this->getData('id'));

        $this->page->addVars(['tailleMax' => Image::MAX_SIZE, 'blogPost' => $blogPost, 'actualImage' => $actualImage]);

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $blogPost = new BlogPost([
                'titre' => $this->postData('titre'),
                'auteur' => $this->postData('auteur'),
                'chapo' => $this->postData('chapo'),
                'contenu' => $this->postData('contenu'),
                'categorie' => $this->postData('categorie'),
                'id' => $this->getData('id')
            ]);

            $image = new Image;
            if ($blogPost->isValid())
            {
                if ($image->tryUpload())
                {
                    if ($image->isValid())
                    {
                        $blogPostManager->update($blogPost);

                        if ($actualImage['id'] !== null)
                        {
                            $image->setId($actualImage['id']);
                            $image->setBlogPostId($actualImage['blogPostId']);
                            $this->executeUpdateImage($image);
                            $this->deleteImageFile($actualImage['serverFile']);
                            $this->user->setMessage('Le blogpost a bien été modifié');
                            $this->redirect('/blogPost/'. $blogPost->id() . '/p1');
                        }
                        else
                        {
                            $image->setBlogPostId($this->getData('id'));
                            $this->executeInsertImage($image);
                            $this->user->setMessage('Le blogpost a bien été modifié');
                            $this->redirect('/blogPost/'. $blogPost->id() . '/p1');
                        }
                    }
                    else
                    {
                        $this->returnFormError($image);
                    }
                }
                else
                {
                    $blogPostManager->update($blogPost);
                    $this->user->setMessage('Le blogpost a bien été modifié');
                    $this->redirect('/blogPost/'. $blogPost->id() . '/p1');
                }
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

    public function executeUpdateImage(Image $image)
    {
        move_uploaded_file($_FILES[$image::UPLOAD]['tmp_name'], $image::IMG_DIR . $image->serverFile());

        $imageManager = new ImageManager;
        $imageManager->update($image);
    }

    public function deleteImageFile ($serverFileImage)
    {
        unlink(Image::IMG_DIR.$serverFileImage);
    }

    public function executeDeleteBlogPost()
    {
        $imageManager = new imageManager;
        $image = $imageManager->getUnique($this->getData('id'));
        $this->deleteImageFile($image['serverFile']);

        $blogPostManager = new BlogPostManager();
        $blogPostManager->delete($this->getData('id'));

        $this->user->setMessage('Le blogpost a bien été supprimé');

        $this->redirect('/index/p1/cat/all');
    }

    public function executeInsertComment()
    {
        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($this->getData('blogPost'));

        $this->page->addVars(['blogPost' => $blogPost]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
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

                $this->redirect('/blogPost/'. $this->getData('blogPost') . '/p1');
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

        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($blogPostId);

        $this->page->addVars(['comment' => $comment, 'blogPost' => $blogPost]);

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

                $this->redirect('/blogPost/'. $blogPostId . '/p1');
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

        $this->redirect('/blogPost/'. $blogPost . '/p1');
    }

    public function returnFormError(Entity $entity)
    {
        $erreurString = '';
        foreach ($entity->erreurs() as $erreur)
        {
            $erreurString .= ' - ' .$erreur . '<br />';
        }
        $this->user->setMessage('Oops !<br />' . $erreurString);
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