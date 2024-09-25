<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925003644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE relic ADD creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE relic ADD CONSTRAINT FK_688191CB61220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_688191CB61220EA6 ON relic (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE relic DROP CONSTRAINT FK_688191CB61220EA6');
        $this->addSql('DROP INDEX IDX_688191CB61220EA6');
        $this->addSql('ALTER TABLE relic DROP creator_id');
    }
}
