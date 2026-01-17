<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250101000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création du nouveau modèle de données : séparation capsule (modèle) et capsule_item (exemplaire) avec migration des données existantes';
    }

    public function up(Schema $schema): void
    {
        // 1. Renommer l'ancienne table capsule
        $this->addSql('ALTER TABLE capsule RENAME TO capsule_old');

        // 2. Création des tables de référence
        $this->addSql('CREATE TABLE producteur (
            id SERIAL PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            maison VARCHAR(255) DEFAULT NULL,
            pays VARCHAR(100) DEFAULT NULL,
            site_web VARCHAR(255) DEFAULT NULL
        )');

        $this->addSql('CREATE TABLE lieu (
            id SERIAL PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            region VARCHAR(255) DEFAULT NULL,
            pays VARCHAR(100) NOT NULL
        )');

        $this->addSql('CREATE TABLE etat (
            id SERIAL PRIMARY KEY,
            libelle VARCHAR(50) NOT NULL,
            note INT NOT NULL,
            description VARCHAR(255) DEFAULT NULL
        )');

        $this->addSql('CREATE TABLE taille (
            id SERIAL PRIMARY KEY,
            libelle VARCHAR(100) NOT NULL,
            diametre_mm DOUBLE PRECISION DEFAULT NULL
        )');

        $this->addSql('CREATE TABLE matiere (
            id SERIAL PRIMARY KEY,
            nom VARCHAR(100) NOT NULL
        )');

        $this->addSql('CREATE TABLE couleur (
            id SERIAL PRIMARY KEY,
            nom VARCHAR(100) NOT NULL,
            code_hex VARCHAR(7) DEFAULT NULL
        )');

        // 3. Insertion des données de référence pour etat
        $this->addSql("INSERT INTO etat (libelle, note, description) VALUES 
            ('Parfait', 5, 'État neuf, aucun défaut visible'),
            ('Très bon', 4, 'Très bon état général, défauts minimes'),
            ('Bon', 3, 'Bon état, quelques défauts visibles'),
            ('Moyen', 2, 'État moyen, défauts notables'),
            ('Abîmé', 1, 'État dégradé, nombreux défauts')
        ");

        // 4. Création de la nouvelle table capsule (modèle)
        $this->addSql('CREATE TABLE capsule (
            id SERIAL PRIMARY KEY,
            producteur_id INT NOT NULL,
            lieu_id INT DEFAULT NULL,
            taille_id INT DEFAULT NULL,
            matiere_id INT DEFAULT NULL,
            couleur_id INT DEFAULT NULL,
            embleme VARCHAR(255) DEFAULT NULL,
            inscription VARCHAR(255) DEFAULT NULL,
            decoration VARCHAR(255) DEFAULT NULL,
            muselet BOOLEAN DEFAULT FALSE NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP NOT NULL,
            updated_at TIMESTAMP NOT NULL
        )');

        // 5. Création de la table capsule_item (exemplaires)
        $this->addSql('CREATE TABLE capsule_item (
            id SERIAL PRIMARY KEY,
            capsule_id INT NOT NULL,
            etat_id INT NOT NULL,
            prix_estime NUMERIC(10, 2) DEFAULT NULL,
            date_acquisition DATE DEFAULT NULL,
            commentaire TEXT DEFAULT NULL
        )');

        // 6. Création des index
        $this->addSql('CREATE INDEX IDX_8006A60CAB9BB300 ON capsule (producteur_id)');
        $this->addSql('CREATE INDEX IDX_8006A60C6AB213CC ON capsule (lieu_id)');
        $this->addSql('CREATE INDEX IDX_8006A60CFF25611A ON capsule (taille_id)');
        $this->addSql('CREATE INDEX IDX_8006A60CF46CD258 ON capsule (matiere_id)');
        $this->addSql('CREATE INDEX IDX_8006A60CC31BA576 ON capsule (couleur_id)');
        $this->addSql('CREATE INDEX IDX_A9D3F9E77227FDD ON capsule_item (capsule_id)');
        $this->addSql('CREATE INDEX IDX_A9D3F9ED5E86FF ON capsule_item (etat_id)');

        // 7. Ajout des contraintes de clés étrangères
        $this->addSql('ALTER TABLE capsule ADD CONSTRAINT FK_8006A60CAB9BB300 FOREIGN KEY (producteur_id) REFERENCES producteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE capsule ADD CONSTRAINT FK_8006A60C6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE capsule ADD CONSTRAINT FK_8006A60CFF25611A FOREIGN KEY (taille_id) REFERENCES taille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE capsule ADD CONSTRAINT FK_8006A60CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE capsule ADD CONSTRAINT FK_8006A60CC31BA576 FOREIGN KEY (couleur_id) REFERENCES couleur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE capsule_item ADD CONSTRAINT FK_A9D3F9E77227FDD FOREIGN KEY (capsule_id) REFERENCES capsule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE capsule_item ADD CONSTRAINT FK_A9D3F9ED5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // 8. Migration des données
        // TODO: À compléter après analyse de la structure de capsule_old
        // Pour l'instant, on garde capsule_old pour ne pas perdre de données
        $this->addSql('COMMENT ON TABLE capsule_old IS \'Ancienne table - à migrer manuellement puis supprimer\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE capsule DROP CONSTRAINT IF EXISTS FK_8006A60CAB9BB300');
        $this->addSql('ALTER TABLE capsule DROP CONSTRAINT IF EXISTS FK_8006A60C6AB213CC');
        $this->addSql('ALTER TABLE capsule DROP CONSTRAINT IF EXISTS FK_8006A60CFF25611A');
        $this->addSql('ALTER TABLE capsule DROP CONSTRAINT IF EXISTS FK_8006A60CF46CD258');
        $this->addSql('ALTER TABLE capsule DROP CONSTRAINT IF EXISTS FK_8006A60CC31BA576');
        $this->addSql('ALTER TABLE capsule_item DROP CONSTRAINT IF EXISTS FK_A9D3F9E77227FDD');
        $this->addSql('ALTER TABLE capsule_item DROP CONSTRAINT IF EXISTS FK_A9D3F9ED5E86FF');
        $this->addSql('DROP TABLE IF EXISTS capsule_item');
        $this->addSql('DROP TABLE IF EXISTS capsule');
        $this->addSql('DROP TABLE IF EXISTS producteur');
        $this->addSql('DROP TABLE IF EXISTS lieu');
        $this->addSql('DROP TABLE IF EXISTS etat');
        $this->addSql('DROP TABLE IF EXISTS taille');
        $this->addSql('DROP TABLE IF EXISTS matiere');
        $this->addSql('DROP TABLE IF EXISTS couleur');
        $this->addSql('ALTER TABLE capsule_old RENAME TO capsule');
    }
}
