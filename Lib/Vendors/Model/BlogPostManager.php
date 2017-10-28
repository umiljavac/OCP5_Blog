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
            $req = $this->db->prepare('INSERT INTO BlogPost SET id = NULL , titre = :titre, auteur = :auteur, chapo = :chapo, contenu = :contenu, dateAjout = NOW(), dateModif = NOW(), categorie = :categorie');

            $req->bindValue(':titre', $blogPost->titre());
            $req->bindValue(':auteur', $blogPost->auteur());
            $req->bindValue(':chapo', $blogPost->chapo());
            $req->bindValue(':contenu', $blogPost->contenu());
            $req->bindValue(':categorie', $blogPost->categorie());

            $req->execute();
        }
        else
        {
            return;
        }
    }

    public function update(BlogPost $blogPost)
    {
        $req = $this->db->prepare('UPDATE BlogPost SET titre = :titre, auteur = :auteur, chapo = :chapo, contenu = :contenu, dateModif = NOW(), categorie = :categorie WHERE id = :id');

        $req->bindValue(':titre', $blogPost->titre());
        $req->bindValue(':auteur', $blogPost->auteur());
        $req->bindValue(':chapo', $blogPost->chapo());
        $req->bindValue(':contenu', $blogPost->contenu());
        $req->bindValue(':categorie', $blogPost->categorie());
        $req->bindValue(':id', $blogPost->id(), \PDO::PARAM_INT);

        $req->execute();
    }

    public function delete($id)
    {
        $this->db->exec('DELETE FROM BlogPost WHERE id = ' . (int) $id);

    }

    public function getList($cat, $limit, $offset)
    {
        $offset --;
        if ($cat === 'all')
        {
            $req = $this->db->query('SELECT id, titre, auteur, chapo, contenu, dateAjout, dateModif, categorie FROM BlogPost ORDER BY id DESC LIMIT ' . (int) $limit .' OFFSET ' . (int) ($offset * $limit));
        }
        else
        {
            $req = $this->db->prepare('SELECT id, titre, auteur, chapo, contenu, dateAjout, dateModif, categorie FROM BlogPost WHERE categorie = :cat ORDER BY id DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int) ($offset * $limit));
            $req->bindValue(':cat', $cat, \PDO::PARAM_STR);
            $req->execute();
        }
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
        $req = $this->db->prepare('SELECT id, titre, auteur, chapo, contenu, dateAjout, dateModif, categorie FROM BlogPost WHERE id = :id');
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

    public function count($cat)
    {
        if ($cat === 'all')
        {
            return $this->db->query('SELECT COUNT(*) FROM BlogPost')->fetchColumn();
        }
        else
        {
            $req = $this->db->prepare('SELECT COUNT(*) FROM BlogPost WHERE categorie = :cat');
            $req->bindValue(':cat', $cat, \PDO::PARAM_STR);
            $req->execute();
            return $req->fetchColumn();
        }
    }

    public function lastInsertId()
    {
        return $this->db->query('SELECT id FROM BlogPost WHERE id = LAST_INSERT_ID()')->fetchColumn();
    }

}