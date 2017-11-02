<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 27/10/2017
 * Time: 16:02
 */

namespace Entity;


use Main\Entity;

class Image extends Entity
{
    protected $blogPostId;
    protected $userFile;
    protected $extension;
    protected $serverFile;
    protected $size;

    const IMG_DIR = __DIR__ .'/../../../Web/imgUp/';
    const UPLOAD = 'image';
    const MAX_SIZE = 2000000;
    const TYPE_AUTH = array('jpg', 'jpeg', 'png');
    const ERROR_PATH = 'Le chemin de la photo est invalide';
    const ERROR_TRANSFERT = 'Erreur lors du transfert';
    const MAX_SIZE_REACHED = 'La taille du fichier ne doit pas excéder 2 Mo';
    const ERROR_FILE_TYPE = 'L\'extension du fichier est invalide';

    public function __construct()
    {
        $this->setUserFile();
        $this->setExtension();
        $this->setSize();
    }

    public function isValid()
    {
        return !(empty($this->userFile) || empty($this->extension) || empty($this->size));
    }

    public function tryUpload()
    {
        return !empty($this->userFile);
    }

    public function setBlogPostId($blogPostId)
    {
        $this->blogPostId = (int) $blogPostId;
        $this->setServerFile();
    }

    public function setUserFile()
    {
        $userFile = isset($_FILES[self::UPLOAD]['name']) ? basename($_FILES[self::UPLOAD]['name']) : null;
        if (is_string($userFile))
        {
            $this->userFile = $userFile;
        }
        else
        {
            $this->erreurs[] = self::ERROR_PATH;
        }
    }

    public function setExtension()
    {
        $extension = strtolower( substr ( strrchr ($this->userFile, '.'), 1));
        if (in_array($extension, self::TYPE_AUTH) === true)
        {
            $this->extension = $extension;
        }
        else
        {
            $this->erreurs[] = self::ERROR_FILE_TYPE;
        }

    }

    public function setServerFile()
    {
        $fichier = $this->userFile;
        $fichier =  strtr($fichier,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        $fichier = preg_replace('/([^.a-z0-9]+)/i', '_', $fichier);
        $this->serverFile = $this->blogPostId . '_' . $fichier;
    }

    public function setSize()
    {
        $size = isset($_FILES[self::UPLOAD]['tmp_name']) ? filesize($_FILES[self::UPLOAD]['tmp_name']) : null;
        $error = isset($_FILES[self::UPLOAD]['error']) ? $_FILES[self::UPLOAD]['error'] : null;
        if ( $error === 2)
        {
            $this->erreurs[] = self::MAX_SIZE_REACHED;
        }
        else
        {
            $this->size = $size;
        }
    }

    public function blogPostId()
    {
        return $this->blogPostId;
    }

    public function userFile()
    {
        return $this->userFile;
    }

    public function extension()
    {
        return $this->extension;
    }

    public function serverFile()
    {
        return $this->serverFile;
    }

    public function size()
    {
        return $this->size;
    }

}