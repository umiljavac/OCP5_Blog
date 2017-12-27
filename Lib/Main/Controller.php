<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 10:13
 */

/**
 * Controller make the connections between the model and the view.
 * That means that he is able to ask to the model which datas are needed to complete the view.
 * Then the controller return them to the application.
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

    }

    public function executeIndex(UserRequest $userRequest)
    {
        $categorie = $userRequest->getData('cat');
        $targetPage = $userRequest->getData('page');

        $this->app->config()->parseFile(__DIR__.'/../../Config/preferences.xml','pagination');
        $blogPostsPerPage = $this->app->config()->getConfig('blogPosts');

        $this->app->config()->parseFile(__DIR__.'/../../Config/selectField.xml','select');
        $selectOptions = $this->app->config()->getLabelsFromString('option');

        $blogPostManager = new BlogPostManager;
        $blogPostList = $blogPostManager->getList($categorie, $blogPostsPerPage, $targetPage);
        $nbBlogPost = $blogPostManager->count($categorie);

        $imageManager = new ImageManager;
        $imageList = $imageManager->getList($categorie, $blogPostsPerPage, $userRequest->getData('page'));

        $this->page->addVars(['blogPostList' => $blogPostList, 'nbBlogPost' => $nbBlogPost, 'categorie' => $categorie, 'imageList' => $imageList, 'blogPostsPerPage' => $blogPostsPerPage, 'selectOptions' => $selectOptions]);
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
            throw new \RuntimeException('le blogPost n\'existe pas');
        }

        $imageManager = new ImageManager;
        $image = $imageManager->getUnique($blogPostId);

        $commentManager = new CommentManager;
        $commentList = $commentManager->getList($blogPostId, $commentsPerPage, $targetPage);
        $nbCommentaires = $commentManager->count($blogPostId);

        $this->page->addVars(['blogPost' => $blogPost, 'image' => $image, 'commentList' => $commentList, 'nbCommentaires' => $nbCommentaires, 'commentsPerPage' => $commentsPerPage]);
    }

    /***********************************************
                    EXECUTE INSERT
     ***********************************************/

    public function executeInsertBlogPost(UserRequest $userRequest)
    {
        $this->app->config()->parseFile(__DIR__.'/../../Config/selectField.xml','select');
        $selectOptions = $this->app->config()->getLabelsFromString('option');
        $this->page->addVars(['tailleMax' => Image::MAX_SIZE, 'selectOptions' => $selectOptions ]);

        if ($userRequest->requestMethod() === 'POST')
        {
            $blogPostData = [
                'titre' => $userRequest->postData('titre'),
                'auteur' => $userRequest->postData('auteur'),
                'chapo' => $userRequest->postData('chapo'),
                'contenu' => $userRequest->postData('contenu'),
                'categorie' => $userRequest->postData('categorie')
            ];

            $blogPostManager = new BlogPostManager();
            $blogPostManager->setEntity(new BlogPost());
            $blogPostManager->hydrate($blogPostData);

            if ($blogPostManager->entity()->isValid() && $blogPostManager->entity()->isNew())
            {
                $imageManager = new ImageManager();
                $imageManager->setEntity(new Image());

                if ($imageManager->entity()->tryUpload())
                {
                    if ($imageManager->entity()->isValid())
                    {
                        $blogPostManager->insert();
                        $blogPostId = $blogPostManager->lastInsertId();
                        $imageManager->entity()->setBlogPostId($blogPostId);
                        $imageManager->insert();

                        $this->app->user()->setMessage('Votre blogpost a bien été ajouté avec une illustration');
                        $this->app->user()->setAttribute('auteur', $blogPostManager->entity()->auteur());
                        $this->app->serverResponse()->redirect('/index/p1/cat/all.html');
                    }
                    else
                    {
                        $this->returnFormError($imageManager->entity()->errors());
                    }
                }
                else
                {
                    $blogPostManager->insert();
                    $this->app->user()->setMessage('Votre blogpost a bien été ajouté sans illustration');
                    $this->app->user()->setAttribute('auteur', $blogPostManager->entity()->auteur());
                    $this->app->serverResponse()->redirect('/index/p1/cat/all.html');
                }
            }
            else
            {
                $this->returnFormError($blogPostManager->entity()->errors());
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

        $this->page->addVars(['blogPost' => $blogPost]);

        if ($userRequest->requestMethod() === 'POST')
        {
            $commentData = [
                'blogPost' => $blogPostId,
                'auteur' => $userRequest->postData('auteur'),
                'contenu' => $userRequest->postData('contenu')
            ];

            $commentManager = new CommentManager;
            $commentManager->setEntity(new Comment());
            $commentManager->hydrate($commentData);

            if ($commentManager->entity()->isValid())
            {
                $commentManager->insert();

                $this->app->user()->setMessage('Votre commentaire a bien été ajouté');
                $this->app->user()->setAttribute('auteur', $commentManager->entity()->auteur());

                $this->app->serverResponse()->redirect('/blogPost/'. $blogPostId . '/p1.html');
            }
            else
            {
                $this->returnFormError($commentManager->entity()->errors());
            }
        }
        else
        {
            return;
        }
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
            throw new \RuntimeException('le blogPost n\'existe pas');
        }

        $imageManager = new ImageManager();
        $actualImage = $imageManager->getUnique($blogPostId);

        $this->app->config()->parseFile(__DIR__.'/../../Config/selectField.xml','select');
        $selectOptions = $this->app->config()->getLabelsFromString('option');

        $this->page->addVars(['tailleMax' => Image::MAX_SIZE, 'blogPost' => $blogPost, 'actualImage' => $actualImage, 'selectOptions' => $selectOptions ]);

        if ($userRequest->requestMethod() === 'POST')
        {
            $blogPostData = [
                'titre' => $userRequest->postData('titre'),
                'auteur' => $userRequest->postData('auteur'),
                'chapo' => $userRequest->postData('chapo'),
                'contenu' => $userRequest->postData('contenu'),
                'categorie' => $userRequest->postData('categorie'),
                'id' => $blogPostId
            ];

            $blogPostManager->setEntity(new BlogPost());
            $blogPostManager->hydrate($blogPostData);

            $imageManager->setEntity(new Image);

            if ($blogPostManager->entity()->isValid())
            {
                if ($imageManager->entity()->tryUpload())
                {
                    if ($imageManager->entity()->isValid())
                    {
                        $blogPostManager->update();

                        if ($actualImage['id'] !== null)
                        {
                            $imageManager->entity()->setId($actualImage['id']);
                            $imageManager->entity()->setBlogPostId($actualImage['blogPostId']);
                            $imageManager->update();
                            $imageManager->deleteImageFile($actualImage['serverFile']);
                            $this->app->user()->setMessage('Le blogpost a bien été modifié');
                            $this->app->serverResponse()->redirect('/blogPost/'. $blogPostId . '/p1.html');
                        }
                        else
                        {
                            $imageManager->entity()->setBlogPostId($blogPostId);
                            $imageManager->insert();
                            $this->app->user()->setMessage('Le blogpost a bien été modifié');
                            $this->app->serverResponse()->redirect('/blogPost/'. $blogPostId . '/p1.html');
                        }
                    }
                    else
                    {
                        $this->returnFormError($imageManager->entity()->errors());
                    }
                }
                else
                {
                    $blogPostManager->update();
                    $this->app->user()->setMessage('Le blogpost a bien été modifié');
                    $this->app->serverResponse()->redirect('/blogPost/'. $blogPostId . '/p1.html');
                }
            }
            else
            {
                $this->returnFormError($blogPostManager->entity()->errors());
            }
        }
        else
        {
            return;
        }
    }

    public function executeUpdateComment(UserRequest $userRequest)
    {
        $commentManager = new commentManager;
        $comment = $commentManager->getUnique($userRequest->getData('id'));

        if (empty ($comment))
        {
            throw new \RuntimeException('le commentaire n\'existe pas');
        }

        $blogPostId = $comment->blogPost();

        $blogPostManager = new BlogPostManager;
        $blogPost = $blogPostManager->getUnique($blogPostId);

        $this->page->addVars(['comment' => $comment, 'blogPost' => $blogPost]);

        if ($userRequest->requestMethod() === 'POST')
        {
            $commentData = [
                'id' => $userRequest->getData('id'),
                'blogPost' => $blogPostId,
                'auteur' => $userRequest->postData('auteur'),
                'contenu' => $userRequest->postData('contenu')
            ];

            $commentManager->setEntity(new Comment());
            $commentManager->hydrate($commentData);

            if ($commentManager->entity()->isValid())
            {
                $commentManager->update();
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
        $imageManager = new ImageManager();
        $image = $imageManager->getUnique($blogPostId);

        if ($image !== null)
        {
            $imageManager->deleteImageFile($image['serverFile']);
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

    /***********************************************
                RETURN FORM ERROR
     ***********************************************/

    public function returnFormError(array $errors)
    {
        $errorString = '';
        foreach ($errors as $error)
        {
            $errorString .= ' - ' .$error . '<br />';
        }
        $this->app->user()->setMessage('Oops !<br />' . $errorString);
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
