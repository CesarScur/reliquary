<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924235927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE relic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE saint_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE relic (id INT NOT NULL, saint_id INT NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_688191CB1E98B21C ON relic (saint_id)');
        $this->addSql('CREATE TABLE saint (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE relic ADD CONSTRAINT FK_688191CB1E98B21C FOREIGN KEY (saint_id) REFERENCES saint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE relic_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE saint_id_seq CASCADE');
        $this->addSql('ALTER TABLE relic DROP CONSTRAINT FK_688191CB1E98B21C');
        $this->addSql('DROP TABLE relic');
        $this->addSql('DROP TABLE saint');
    }
}
