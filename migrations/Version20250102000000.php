<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250102000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration des données de capsule_old vers le nouveau modèle capsule/capsule_item';
    }

    public function up(Schema $schema): void
    {
        // 1. Créer d'abord les entrées par défaut
        $this->addSql("INSERT INTO producteur (nom) VALUES ('Inconnu')");
        $this->addSql("INSERT INTO lieu (nom, pays) VALUES ('Inconnu', 'France')");
        $this->addSql("INSERT INTO taille (libelle, diametre_mm) VALUES ('Normal', 31.0)");
        $this->addSql("INSERT INTO couleur (nom) VALUES ('Inconnu')");

        // 2. Peupler les tables de référence à partir de capsule_old
        
        // Producteurs (en excluant NULL et 'Inconnu' déjà créé)
        $this->addSql("
            INSERT INTO producteur (nom)
            SELECT DISTINCT producteur
            FROM capsule_old 
            WHERE producteur IS NOT NULL 
            AND producteur != 'Inconnu'
            AND TRIM(producteur) != ''
        ");

        // Lieux (en excluant NULL et 'Inconnu' déjà créé)
        $this->addSql("
            INSERT INTO lieu (nom, pays)
            SELECT DISTINCT lieu, 'France'
            FROM capsule_old 
            WHERE lieu IS NOT NULL 
            AND lieu != 'Inconnu'
            AND TRIM(lieu) != ''
        ");

        // Tailles (en excluant 'Normal' déjà créé)
        $this->addSql("
            INSERT INTO taille (libelle, diametre_mm)
            SELECT DISTINCT 
                taille,
                CASE 
                    WHEN taille = 'Magnum' THEN 34.0
                    ELSE NULL
                END
            FROM capsule_old 
            WHERE taille IS NOT NULL 
            AND taille != 'Normal'
            AND TRIM(taille) != ''
        ");

        // Couleurs (en excluant NULL et 'Inconnu' déjà créé)
        $this->addSql("
            INSERT INTO couleur (nom)
            SELECT DISTINCT couleur
            FROM capsule_old 
            WHERE couleur IS NOT NULL 
            AND couleur != 'Inconnu'
            AND TRIM(couleur) != ''
        ");

        // 3. Créer les capsules (modèles) - une par combinaison unique
        $this->addSql("
            INSERT INTO capsule (
                producteur_id,
                lieu_id,
                taille_id,
                couleur_id,
                inscription,
                decoration,
                muselet,
                image,
                created_at,
                updated_at
            )
            SELECT DISTINCT ON (
                COALESCE(NULLIF(TRIM(c.producteur), ''), 'Inconnu'),
                COALESCE(NULLIF(TRIM(c.couleur), ''), 'Inconnu'),
                COALESCE(NULLIF(TRIM(c.inscription), ''), ''),
                COALESCE(NULLIF(TRIM(c.decoration), ''), '')
            )
                p.id as producteur_id,
                l.id as lieu_id,
                t.id as taille_id,
                co.id as couleur_id,
                NULLIF(TRIM(c.inscription), '') as inscription,
                NULLIF(TRIM(c.decoration), '') as decoration,
                COALESCE(c.coffret, false) as muselet,
                NULLIF(TRIM(c.image), '') as image,
                NOW() as created_at,
                NOW() as updated_at
            FROM capsule_old c
            LEFT JOIN producteur p ON p.nom = COALESCE(NULLIF(TRIM(c.producteur), ''), 'Inconnu')
            LEFT JOIN lieu l ON l.nom = COALESCE(NULLIF(TRIM(c.lieu), ''), 'Inconnu')
            LEFT JOIN taille t ON t.libelle = COALESCE(NULLIF(TRIM(c.taille), ''), 'Normal')
            LEFT JOIN couleur co ON co.nom = COALESCE(NULLIF(TRIM(c.couleur), ''), 'Inconnu')
            ORDER BY 
                COALESCE(NULLIF(TRIM(c.producteur), ''), 'Inconnu'),
                COALESCE(NULLIF(TRIM(c.couleur), ''), 'Inconnu'),
                COALESCE(NULLIF(TRIM(c.inscription), ''), ''),
                COALESCE(NULLIF(TRIM(c.decoration), ''), ''),
                c.id
        ");

        // 4. Créer les capsule_item (exemplaires) en fonction de la quantité
        $this->addSql("
            INSERT INTO capsule_item (
                capsule_id,
                etat_id,
                prix_estime,
                date_acquisition,
                commentaire
            )
            SELECT 
                cap.id as capsule_id,
                CASE 
                    WHEN old.etat >= 5 THEN (SELECT id FROM etat WHERE note = 5 LIMIT 1)
                    WHEN old.etat >= 4 THEN (SELECT id FROM etat WHERE note = 4 LIMIT 1)
                    WHEN old.etat >= 3 THEN (SELECT id FROM etat WHERE note = 3 LIMIT 1)
                    WHEN old.etat >= 2 THEN (SELECT id FROM etat WHERE note = 2 LIMIT 1)
                    ELSE (SELECT id FROM etat WHERE note = 1 LIMIT 1)
                END as etat_id,
                old.prix as prix_estime,
                NULL as date_acquisition,
                NULL as commentaire
            FROM capsule_old old
            LEFT JOIN producteur p ON p.nom = COALESCE(NULLIF(TRIM(old.producteur), ''), 'Inconnu')
            LEFT JOIN couleur co ON co.nom = COALESCE(NULLIF(TRIM(old.couleur), ''), 'Inconnu')
            INNER JOIN capsule cap ON 
                cap.producteur_id = p.id
                AND cap.couleur_id = co.id
                AND COALESCE(NULLIF(TRIM(cap.inscription), ''), '') = COALESCE(NULLIF(TRIM(old.inscription), ''), '')
                AND COALESCE(NULLIF(TRIM(cap.decoration), ''), '') = COALESCE(NULLIF(TRIM(old.decoration), ''), '')
            CROSS JOIN generate_series(1, GREATEST(COALESCE(old.quantite, 1), 1)) as series
        ");

        // 5. Supprimer l'ancienne table
        $this->addSql('DROP TABLE capsule_old');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('-- Migration irréversible : les données ont été transformées');
        $this->throwIrreversibleMigrationException();
    }
}
