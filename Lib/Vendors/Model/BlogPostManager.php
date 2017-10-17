<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/09/2017
 * Time: 22:57
 */
namespace Model;

use \Entity\BlogPost;
use Main\Manager;

class BlogPostManager extends Manager
{
    public function insert(BlogPost $blogPost)
    {
        if($blogPost->isNew())
        {
            $req = $this->db->prepare('INSERT INTO BlogPost SET id = NULL , titre = :titre, auteur = :auteur, chapo = :chapo, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');

            $req->bindValue(':titre', $blogPost->titre());
            $req->bindValue(':auteur', $blogPost->auteur());
            $req->bindValue(':chapo', $blogPost->chapo());
            $req->bindValue(':contenu', $blogPost->contenu());

            $req->execute();
            //ajout de la veille 6/10/2017
       /*     $req = $this->db->query('SELECT id FROM BlogPost WHERE id = last_insert_id()');
            $lastBlogPostId = $req->fetchColumn();
            return $lastBlogPostId; */
        }
        else
        {
            return;
        }
    }

    public function update(BlogPost $blogPost)
    {
        $req = $this->db->prepare('UPDATE BlogPost SET titre = :titre, auteur = :auteur, chapo = :chapo, contenu = :contenu, dateModif = NOW() WHERE id = :id');

        $req->bindValue(':titre', $blogPost->titre());
        $req->bindValue(':auteur', $blogPost->auteur());
        $req->bindValue(':chapo', $blogPost->chapo());
        $req->bindValue(':contenu', $blogPost->contenu());
        $req->bindValue(':id', $blogPost->id(), \PDO::PARAM_INT);

        $req->execute();
    }

    public function delete($id)
    {
        $this->db->exec('DELETE FROM BlogPost WHERE id = ' . (int) $id);

    }

    public function getList($limit, $offset)
    {
        $offset --;
       $req = $this->db->query('SELECT id, titre, auteur, chapo, contenu, dateAjout, dateModif FROM BlogPost ORDER BY id DESC LIMIT ' . (int) $limit .' OFFSET ' . (int) ($offset * $limit));
        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\BlogPost');
        $blogPostList = $req->fetchAll();

        foreach ($blogPostList as $blogPost)
        {
            $blogPost->setDateAjout(new \DateTime($blogPost->dateAjout()));
            $blogPost->setDateModif(new \DateTime($blogPost->dateModif()));
        }

        $req->closeCursor();
        return $blogPostList;
    }

    public function getUnique($id)
    {
        $req = $this->db->prepare('SELECT id, titre, auteur, chapo, contenu, dateAjout, dateModif FROM BlogPost WHERE id = :id');
        $req->bindValue(':id', (int) $id,\PDO::PARAM_INT);
        $req->execute();

        $req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\BlogPost');

        if ($blogPost = $req->fetch())
        {
            $blogPost->setDateAjout(new \DateTime($blogPost->dateAjout()));
            $blogPost->setDateModif(new \DateTime($blogPost->dateModif()));

            return $blogPost;
        }
        return null;
    }

    public function count()
    {
        return $this->db->query('SELECT COUNT(*) FROM BlogPost')->fetchColumn();
    }

}