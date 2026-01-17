<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240822195315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capsule ALTER COLUMN producteur DROP NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN embleme DROP NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN couleur DROP NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN matiere DROP NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN inscription DROP NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN decoration DROP NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN lieu DROP NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN prix DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capsule ALTER COLUMN producteur SET NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN embleme SET NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN couleur SET NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN matiere SET NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN inscription SET NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN decoration SET NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN lieu SET NOT NULL');
        $this->addSql('ALTER TABLE capsule ALTER COLUMN prix SET NOT NULL');
    }
}
