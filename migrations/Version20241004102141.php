<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004102141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cover_letter ADD job_offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cover_letter ADD CONSTRAINT FK_EBE6B473481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EBE6B473481D195 ON cover_letter (job_offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cover_letter DROP FOREIGN KEY FK_EBE6B473481D195');
        $this->addSql('DROP INDEX UNIQ_EBE6B473481D195 ON cover_letter');
        $this->addSql('ALTER TABLE cover_letter DROP job_offer_id');
    }
}
