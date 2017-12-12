<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 23:14
 */

/**
 * Class CommentManager is used to request the Comment table in the database.
 * Methods allow to select, insert, update, delete entries in this table.
 */


namespace Model;

use \Entity\Comment;
use Main\Manager;

class CommentManager extends Manager
{
    public function insert(Comment $comment)
    {
        $req = $this->db->prepare('INSERT INTO Comment SET id = NULL, blogPost = :blogPost, auteur = :auteur, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');
        $req->bindValue(':blogPost', $comment->blogPost(), \PDO::PARAM_INT);
        $req->bindValue(':auteur', $comment->auteur());
        $req->bindValue(':contenu', $comment->contenu());

        $req->execute();

        $comment->setId($this->db->lastInsertId()); // à vérifier
    }

    public function getUnique($id)
    {
        $req = $this->db->prepare('SELECT id, blogPost, auteur, contenu FROM Comment WHERE id = :id');
        $req->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
        $req->execute();

        if ($comment = $req->fetch())
        {
            return $comment;
        }
        else
        {
            return null;
        }
    }

    public function update(Comment $comment)
    {
        $req = $this->db->prepare('UPDATE Comment SET auteur = :auteur, contenu = :contenu, dateModif = NOW() WHERE id = :id');
        $req->bindValue(':auteur', $comment->auteur());
        $req->bindValue(':contenu', $comment->contenu());
        $req->bindValue(':id', $comment->id(), \PDO::PARAM_INT );

        $req->execute();
    }

    public function delete($id)
    {
        $blogPost = $this->db->query('SELECT blogPost FROM Comment WHERE id = ' . (int) $id)->fetchColumn();
        $this->db->exec('DELETE FROM Comment WHERE id = '. (int) $id);

        return $blogPost;
    }

    public function getList($blogPost, $limit, $offset)
    {
        $offset --;
        $req = $this->db->prepare('SELECT * FROM Comment WHERE blogPost = :blogPost ORDER BY id DESC LIMIT ' . (int) $limit. ' OFFSET ' .  (int) ($limit * $offset));
        $req->bindValue(':blogPost', (int) $blogPost, \PDO::PARAM_INT);
        $req->execute();

        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
        $commentList = $req->fetchAll();

        foreach ($commentList as $comment)
        {
            $comment->setDateAjout(new \DateTime($comment->dateAjout()));
            $comment->setDateModif(new \DateTime($comment->dateModif()));
        }
        return $commentList;
    }

    public function count($id)
    {
        return $this->db->query('SELECT COUNT(*) FROM Comment WHERE blogPost = ' . (int) $id)->fetchColumn();
    }
}
