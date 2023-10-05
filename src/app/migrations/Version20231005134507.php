<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005134507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "available_event_day" (id UUID NOT NULL, event_id UUID NOT NULL, day INT NOT NULL, month INT NOT NULL, year INT NOT NULL, seats INT NOT NULL, version INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "available_event_day".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "available_event_day".event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "event" (id UUID NOT NULL, event_name VARCHAR(300) NOT NULL, version INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "event".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "reserved_event_day" (id UUID NOT NULL, event_id UUID NOT NULL, day INT NOT NULL, month INT NOT NULL, year INT NOT NULL, seats INT NOT NULL, version INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "reserved_event_day".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "reserved_event_day".event_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE "available_event_day"');
        $this->addSql('DROP TABLE "event"');
        $this->addSql('DROP TABLE "reserved_event_day"');
    }
}
