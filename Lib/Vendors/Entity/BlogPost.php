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
    protected $titre;
    protected $chapo;
    protected $categorie;

    const TITRE_INVALIDE = 'le champs "titre" est vide';
    const CHAPO_INVALIDE = 'le champs "chapô" est vide';

    public function isValid()
    {
        return !(empty($this->auteur) || empty($this->contenu) || empty($this->titre) || empty($this->chapo));
    }


    /***********************************************
                        GETTERS
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

}