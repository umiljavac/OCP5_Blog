<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 23:30
 */

/**
 *Abstract class Entity implements ArrayAccess interface
 * used as parent class of BlogPost, Image, Comment classes.
 * The constructor need an array of data and use the Hydrator trait to set the properties of the instance of the inherited class
 * Constants are used to reply a message when the form used by the user to insert or update an Entity child (blogpost, Comment, Image) is incomplete or invalid.
 */

namespace Main;


abstract class Entity implements \ArrayAccess
{
    use Hydrator;

    protected $id;
    protected $erreurs = [];
    protected $auteur;
    protected $contenu;
    protected $dateAjout;
    protected $dateModif;

    const AUTEUR_INVALIDE = 'le champs "auteur" est vide';
    const CONTENU_INVALIDE = 'le champs "contenu" est vide';

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    public function isNew()
    {
        return empty($this->id);
    }

    /***********************************************
                        SETTERS
     ***********************************************/

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function setAuteur($auteur)
    {
        if (!is_string($auteur) || empty($auteur)) {
            $this->erreurs[] = self::AUTEUR_INVALIDE;
        }

        $this->auteur = $auteur;
    }

    public function setContenu($contenu)
    {
        if (!is_string($contenu) || empty($contenu)) {
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

    public function id()
    {
        return $this->id;
    }

    public function erreurs()
    {
        return $this->erreurs;
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

    //

   public function offsetGet($var)
    {
        if (isset($this->$var) && is_callable([$this, $var]))
        {
            return $this->$var();
        }
    }

    public function offsetSet($var, $value)
    {
        $method = 'set'.ucfirst($var);

        if (isset($this->$var) && is_callable([$this, $method]))
        {
            $this->$method($value);
        }
    }

    public function offsetExists($var)
    {
        return isset($this->$var) && is_callable([$this, $var]);
    }

    public function offsetUnset($var)
    {
        throw new \Exception('Impossible de supprimer une quelconque valeur');
    }

}

