<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004102009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EB944729C');
        $this->addSql('DROP INDEX IDX_288A3A4EB944729C ON job_offer');
        $this->addSql('ALTER TABLE job_offer DROP cover_letter_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer ADD cover_letter_id INT NOT NULL');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EB944729C FOREIGN KEY (cover_letter_id) REFERENCES cover_letter (id)');
        $this->addSql('CREATE INDEX IDX_288A3A4EB944729C ON job_offer (cover_letter_id)');
    }
}
