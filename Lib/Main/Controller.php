<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 10:13
 */

/**
 * Controller make the connections between the model and the view.
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
    protected $app;
    protected $page = null;
    protected $actionView;

    public function __construct($actionView, $app)
    {
        $this->app = $app;
        $this->page = new Page();
        $this->setActionView($actionView);
    }


    /***********************************************
                        EXECUTE
     ***********************************************/

    public function execute()
    {
        $actionRequest = 'execute'.ucfirst($this->actionView);
        if (!is_callable([$this, $actionRequest]))
        {
            throw new \RuntimeException('L\'action demandée est impossible');
        }

        $this->$actionRequest($this->app->userRequest());
    }

    /***********************************************
                    EXECUTE SHOW
     ***********************************************/

    public function executeAccueil()
    {
        $this->app->config()->parseFile(__DIR__.'/../../Config/homeLinks.xml','link');
        $cv = $this->app->config()->getconfig('cv');
        $this->page->addVars(['cv' => $cv]);
    }

    public function executeIndex(UserRequest $userRequest)
    {
        $categorie = $userRequest->getData('cat');
        $targetPage = $userRequest->getData('page');

        $this->app->config()->parseFile(__DIR__.'/../../Config/preferences.xml','pagination');
        $blogPostsPerPage = $this->app->config()->getconfig('blogPosts');

        $blogPostManager = new BlogPostManager;
        $blogPostList = $blogPostManager->getList($categorie, $blogPostsPerPage, $targetPage);
        $nbBlogPost = $blogPostManager->count($categorie);

        $imageManager = new ImageManager;
        $imageList = $imageManager->getList($categorie, $blogPostsPerPage, $userRequest->getData('page'));

        $this->page->addVars(['blogPostList' => $blogPostList, 'nbBlogPost' => $nbBlogPost, 'categorie' => $categorie, 'imageList' => $imageList, 'user' => $this->app->user(), 'blogPostsPerPage' => $blogPostsPerPage]);
    }

    public function executeShowBlogPost(UserRequest $userRequest)
    {
        $blogPostId = $userRequest->getData('id');
        $targetPage = $userRequest->getData('page');

        $this->app->config()->parseFile(__DIR__.'/../../Config/preferences.xml','pagination');
        $commentsPerPage = $this->app->config()->getconfig('comments');

        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($blogPostId);

        if (empty($blogPost))
        {
            $this->app->serverResponse()->redirect404();
        }

        $imageManager = new ImageManager;
        $image = $imageManager->getUnique($blogPostId);

        $commentManager = new CommentManager;
        $commentList = $commentManager->getList($blogPostId, $commentsPerPage, $targetPage);
        $nbCommentaires = $commentManager->count($blogPostId);

        $this->page->addVars(['blogPost' => $blogPost, 'image' => $image, 'commentList' => $commentList, 'nbCommentaires' => $nbCommentaires, 'user' => $this->app->user(), 'commentsPerPage' => $commentsPerPage]);
    }

    /***********************************************
                    EXECUTE INSERT
     ***********************************************/

    public function executeInsertBlogPost(UserRequest $userRequest)
    {
        $this->page->addVars(['tailleMax' => Image::MAX_SIZE, 'user' => $this->app->user()]);

        if ($userRequest->requestMethod() === 'POST')
        {
            $blogPost = new BlogPost([
                'titre' => $userRequest->postData('titre'),
                'auteur' => $userRequest->postData('auteur'),
                'chapo' => $userRequest->postData('chapo'),
                'contenu' => $userRequest->postData('contenu'),
                'categorie' => $userRequest->postData('categorie')
            ]);

            if ($blogPost->isValid())
            {
                $blogPostManager = new BlogPostManager;
                $image = new Image;

                if ($image->tryUpload())
                {
                    if ($image->isValid())
                    {
                        $blogPostManager->insert($blogPost);
                        $blogPostId = $blogPostManager->lastInsertId();
                        $image->setBlogPostId($blogPostId);
                        $this->executeInsertImage($image);

                        $this->app->user()->setMessage('Votre blogpost a bien été ajouté avec une illustration');
                        $this->app->user()->setAttribute('auteur', $blogPost->auteur());

                        $this->app->serverResponse()->redirect('/index/p1/cat/all.html');
                    }
                    else
                    {
                        $this->returnFormError($image);
                    }
                }
                else
                {
                    $blogPostManager->insert($blogPost);

                    $this->app->user()->setMessage('Votre blogpost a bien été ajouté sans illustration');
                    $this->app->user()->setAttribute('auteur', $blogPost->auteur());
                    $this->app->serverResponse()->redirect('/index/p1/cat/all.html');
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

    public function executeInsertComment(UserRequest $userRequest)
    {
        $blogPostId = $userRequest->getData('blogPostId');
        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($blogPostId);

        $this->page->addVars(['blogPost' => $blogPost, 'user' => $this->app->user()]);

        if ($userRequest->requestMethod() === 'POST')
        {
            $comment = new Comment([
                'blogPost' => $blogPostId,
                'auteur' => $userRequest->postData('auteur'),
                'contenu' => $userRequest->postData('contenu')
            ]);

            if ($comment->isValid())
            {
                $commentManager = new CommentManager;
                $commentManager->insert($comment);

                $this->app->user()->setMessage('Votre commentaire a bien été ajouté');
                $this->app->user()->setAttribute('auteur', $comment->auteur());

                $this->app->serverResponse()->redirect('/blogPost/'. $blogPostId . '/p1.html');
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

    public function executeInsertImage(Image $image)
    {
        move_uploaded_file($_FILES[$image::UPLOAD]['tmp_name'], $image::IMG_DIR . $image->serverFile());

        $imageManager = new ImageManager;
        $imageManager->insert($image);
    }

    /***********************************************
    EXECUTE UPDATE
     ***********************************************/

    public function executeUpdateBlogPost(UserRequest $userRequest)
    {
        $blogPostId = $userRequest->getData('id');
        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($blogPostId);

        if (empty($blogPost))
        {
            $this->app->serverResponse()->redirect404();
        }

        $imageManager = new ImageManager;
        $actualImage = $imageManager->getUnique($blogPostId);

        $this->page->addVars(['tailleMax' => Image::MAX_SIZE, 'blogPost' => $blogPost, 'actualImage' => $actualImage, 'user' => $this->app->user()]);

        if ($userRequest->requestMethod() === 'POST')
        {
            $blogPost = new BlogPost([
                'titre' => $userRequest->postData('titre'),
                'auteur' => $userRequest->postData('auteur'),
                'chapo' => $userRequest->postData('chapo'),
                'contenu' => $userRequest->postData('contenu'),
                'categorie' => $userRequest->postData('categorie'),
                'id' => $blogPostId
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
                            $this->app->user()->setMessage('Le blogpost a bien été modifié');
                            $this->app->serverResponse()->redirect('/blogPost/'. $blogPost->id() . '/p1.html');
                        }
                        else
                        {
                            $image->setBlogPostId($blogPostId);
                            $this->executeInsertImage($image);
                            $this->app->user()->setMessage('Le blogpost a bien été modifié');
                            $this->app->serverResponse()->redirect('/blogPost/'. $blogPost->id() . '/p1.html');
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
                    $this->app->user()->setMessage('Le blogpost a bien été modifié');
                    $this->app->serverResponse()->redirect('/blogPost/'. $blogPost->id() . '/p1.html');
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

    public function executeUpdateComment(UserRequest $userRequest)
    {
        $commentManager = new commentManager;
        $comment = $commentManager->getUnique($userRequest->getData('id'));

        if (empty ($comment))
        {
            $this->app->serverResponse()->redirect404();
        }

        $blogPostId = $comment->blogPost();

        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($blogPostId);

        $this->page->addVars(['comment' => $comment, 'blogPost' => $blogPost, 'user' => $this->app->user()]);

        if ($userRequest->requestMethod() === 'POST')
        {
            $comment = new Comment([
                'id' => $userRequest->getData('id'),
                'blogPost' => $blogPostId,
                'auteur' => $userRequest->postData('auteur'),
                'contenu' => $userRequest->postData('contenu')
            ]);

            if ($comment->isValid())
            {
                $commentManager->update($comment);
                $this->app->user()->setMessage('Le commentaire a bien été modifié');
                $this->app->serverResponse()->redirect('/blogPost/'. $blogPostId . '/p1.html');
            }
        }
        else
        {
            return;
        }
    }

    /***********************************************
                    EXECUTE DELETE
     ***********************************************/

    public function executeDeleteBlogPost(UserRequest $userRequest)
    {
        $blogPostId = $userRequest->getData('id');
        $imageManager = new imageManager;
        $image = $imageManager->getUnique($blogPostId);

        if ($image != null)
        {
            $this->deleteImageFile($image['serverFile']);
        }

        $blogPostManager = new BlogPostManager();
        $blogPostManager->delete($blogPostId);

        $this->app->user()->setMessage('Le blogpost a bien été supprimé');
        $this->app->serverResponse()->redirect('/index/p1/cat/all.html');
    }

    public function executeDeleteComment(UserRequest $userRequest)
    {
        $commentManager = new CommentManager;
        $blogPost = $commentManager->delete($userRequest->getData('id'));

        $this->app->user()->setMessage('Le commentaire a bien été supprimé');

        $this->app->serverResponse()->redirect('/blogPost/'. $blogPost . '/p1.html');
    }

    public function deleteImageFile ($serverFileImage)
    {
        unlink(Image::IMG_DIR.$serverFileImage);
    }

    /***********************************************
                RETURN FORM ERROR
     ***********************************************/

    public function returnFormError(Entity $entity)
    {
        $erreurString = '';
        foreach ($entity->erreurs() as $erreur)
        {
            $erreurString .= ' - ' .$erreur . '<br />';
        }
        $this->app->user()->setMessage('Oops !<br />' . $erreurString);
    }


    /***********************************************
                        SETTERS
     ***********************************************/

    public function setActionView($actionView)
    {
        if (!is_string($actionView) || empty($actionView))
        {
            throw new \InvalidArgumentException('La vue doit être une chaine de caractères valide');
        }

        $this->actionView = $actionView;
        $this->page->setFileView(__DIR__ . '/../../Views/'. $this->actionView . '.php');
    }

    /***********************************************
                    GETTERS
     ***********************************************/

    public function page()
    {
        return $this->page;
    }


    public function actionView()
    {
        return $this->actionView;
    }
}
