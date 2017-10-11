<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 23:07
 */

namespace Entity;

use Main\Entity;

class Comment extends Entity
{
    protected $blogPost;

    public function isValid()
    {
        return !(empty($this->auteur) || empty($this->contenu) || empty($this->blogPost));
    }

    public function setBlogPost($blogPost)
    {
        $this->blogPost = (int) $blogPost;
    }

    public function blogPost()
    {
        return $this->blogPost;
    }
}