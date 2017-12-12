<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 27/10/2017
 * Time: 16:04
 */

/**
 * Class ImageManager is used to request the Image table in the database.
 * Methods allow to select, insert, update, delete entries in this table.
 */

namespace Model;


use Main\Manager;
use Entity\Image;

class ImageManager extends Manager
{
    public function insert(Image $image)
    {
        $req = $this->db->prepare('INSERT INTO Image SET id = NULL, blogPostId = :blogPostId, userFile = :userFile, extension = :extension, serverFile = :serverFile, size = :size');
        $req->bindValue(':blogPostId', $image->blogPostId(), \PDO::PARAM_INT);
        $req->bindValue(':userFile', $image->userFile(), \PDO::PARAM_STR);
        $req->bindValue(':extension', $image->extension(), \PDO::PARAM_STR);
        $req->bindValue(':serverFile', $image->serverFile(), \PDO::PARAM_STR);
        $req->bindValue(':size', $image->size(), \PDO::PARAM_STR);

        $req->execute();
    }

    public function getUnique($blogPostId)
    {
        $req = $this->db->prepare('SELECT id, blogPostId, serverFile, userFile FROM Image WHERE blogPostId = :blogPostId');
        $req->bindValue(':blogPostId', $blogPostId, \PDO::PARAM_INT);
        $req->execute();
        if ($image = $req->fetch())
        {
            return $image;
        }
        else
        {
            return null;
        }
    }

    public function getList($cat, $limit, $offset)
    {
        $offset --;
        if ($cat === 'all')
        {
            $req = $this->db->query('SELECT id, blogPostId, serverFile FROM Image ORDER BY blogPostId DESC LIMIT ' . (int) $limit .' OFFSET ' . (int) ($offset * $limit));
        }
        else
        {
            $req = $this->db->prepare('SELECT Image.id, blogPostId, serverFile FROM Image INNER JOIN BlogPost ON Image.blogPostId = BlogPost.id WHERE categorie = :cat ORDER BY blogPost.id DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int) ($offset * $limit));
            $req->bindValue(':cat', $cat, \PDO::PARAM_STR);
            $req->execute();
        }
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Image');
        $imageList = $req->fetchAll();

        $req->closeCursor();
        return $imageList;
    }

    public function update(Image $image)
    {
        $req = $this->db->prepare('UPDATE Image set userFile = :userFile, extension = :extension, serverFile = :serverFile, size = :size WHERE id = :id');

        $req->bindValue(':userFile', $image->userFile(), \PDO::PARAM_STR);
        $req->bindValue(':extension', $image->extension(), \PDO::PARAM_STR);
        $req->bindValue(':serverFile', $image->serverFile(), \PDO::PARAM_STR);
        $req->bindValue(':size', $image->size(), \PDO::PARAM_INT);
        $req->bindValue(':id', $image->id(), \PDO::PARAM_INT);

        $req->execute();
    }
}
