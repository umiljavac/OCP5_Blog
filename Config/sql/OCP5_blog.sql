DROP DATABASE IF EXISTS OCP5_blog;
CREATE DATABASE OCP5_blog CHARACTER SET 'utf8';
USE OCP5_blog;
CREATE TABLE BlogPost (
  id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  titre VARCHAR(100) NOT NULL,
  auteur VARCHAR(100) NOT NULL,
  chapo VARCHAR(255) NOT NULL,
  contenu TEXT NOT NULL,
  dateAjout DATETIME NOT NULL,
  dateModif DATETIME DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE = InnoDB;

INSERT INTO BlogPost VALUES
  (NULL, 'Le Jazz', 'Javacrisp', 'Une petite mise en bouche sur le Jazz',
   'Je n\'ai rien à dire de très pértinent je vais donc déblatérer des trucs..', NOW(), NOW()),
  (NULL, 'Le Jazzo', 'Javacrisp', 'Une petite mise en bouche sur le Jazz volume 2',
   'Je n\'ai rien à dire de très pértinent je vais donc déblatérer des trucs encore plus insipides !', NOW(), NOW()),
  (NULL, 'Le Jazzul', 'Javacrisp', 'Une petite mise en bouche sur le Jazz volume 3',
   'Je n\'ai rien à dire de très pértinent je vais donc déblatérer des truc encore plus longtemps !', NOW(), NOW());

CREATE TABLE Comment (
  id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  blogPost MEDIUMINT UNSIGNED NOT NULL,
  auteur VARCHAR(100) NOT NULL,
  contenu TEXT NOT NULL,
  dateAjout DATETIME NOT NULL,
  dateModif DATETIME DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE = InnoDB;

INSERT INTO Comment VALUES
  (NULL, 1, 'Fats le Cat', 'Superbe article !', NOW(), NOW()),
  (NULL, 1, 'Fats le Cat', 'Vraiment un superbe article, je le redis !', NOW(), NOW()),
  (NULL, 2, 'Felix Pix', 'C\'est trop redondant .. ', NOW(), NOW()),
  (NULL, 3, 'Felix Pix', ' Où est l\'article ?', NOW(), NOW());

-- A ajouter après ..
ALTER TABLE Comment ADD FOREIGN KEY (blogPost) REFERENCES BlogPost(id) ON DELETE CASCADE;