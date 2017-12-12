<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 23:07
 */

/**
 * Class Comment is used to represent and manipulate a comment.
 * This class extends the Entity class.
 */

namespace Entity;

use Main\Entity;

class Comment extends Entity
{
    protected $blogPost;
    protected $auteur;
    protected $contenu;
    protected $dateAjout;
    protected $dateModif;

    const AUTEUR_INVALIDE = 'le champs "auteur" est vide';
    const CONTENU_INVALIDE = 'le champs "contenu" est vide';

    public function isValid()
    {
        return !(empty($this->auteur) || empty($this->contenu) || empty($this->blogPost));
    }

    /***********************************************
                     SETTER
     ***********************************************/

    public function setBlogPost($blogPost)
    {
        $this->blogPost = (int) $blogPost;
    }

    public function setAuteur($auteur)
    {
        if(!is_string($auteur) || empty($auteur))
        {
            $this->erreurs[] = self::AUTEUR_INVALIDE;
        }

        $this->auteur = $auteur;
    }

    public function setContenu($contenu)
    {
        if(!is_string($contenu) || empty($contenu))
        {
            $this->erreurs[] = self::CONTENU_INVALIDE;
        }

        $this->contenu = $contenu;
    }

    public function setDateAjout(\DateTime $dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }

    public function setDateModif(\DateTime $dateModif)
    {
        $this->dateModif = $dateModif;
    }

    /***********************************************
                     GETTER
     ***********************************************/

    public function blogPost()
    {
        return $this->blogPost;
    }

    public function auteur()
    {
        return $this->auteur;
    }

    public function contenu()
    {
        return $this->contenu;
    }

    public function dateAjout()
    {
        return $this->dateAjout;
    }

    public function dateModif()
    {
        return $this->dateModif;
    }
}
