<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 23:06
 */

/**
 * Class BlogPost is used to represent or manipulate a blogpost.
 * This class extends the Entity class.
 */

namespace Entity;

use \Main\Entity;

class BlogPost extends Entity
{
    protected $auteur;
    protected $titre;
    protected $chapo;
    protected $contenu;
    protected $dateAjout;
    protected $dateModif;
    protected $categorie;

    const AUTEUR_INVALIDE = 'le champs "auteur" est vide';
    const CONTENU_INVALIDE = 'le champs "contenu" est vide';
    const TITRE_INVALIDE = 'le champs "titre" est vide';
    const CHAPO_INVALIDE = 'le champs "chapô" est vide';

    public function isValid()
    {
        return !(empty($this->auteur) || empty($this->contenu) || empty($this->titre) || empty($this->chapo));
    }


    /***********************************************
                        SETTERS
     ***********************************************/

    public function setTitre($titre)
    {
        if(!is_string($titre) || empty($titre))
        {
            $this->erreurs[] = self::TITRE_INVALIDE;
        }

        $this->titre = $titre;
    }

    public function setChapo($chapo)
    {
        if(!is_string($chapo) || empty($chapo))
        {
            $this->erreurs[] = self::CHAPO_INVALIDE;
        }

        $this->chapo = $chapo;
    }

    public function setCategorie($cat)
    {
        $this->categorie = $cat;
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
                        GETTERS
     ***********************************************/

    public function titre()
    {
        return $this->titre;
    }

    public function chapo()
    {
        return $this->chapo;
    }

    public function categorie()
    {
        return $this->categorie;
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