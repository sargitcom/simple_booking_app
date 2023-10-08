<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008142908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "reservation" (id UUID NOT NULL, event_id UUID NOT NULL, reservation_id UUID NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, seats INT NOT NULL, version INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "reservation".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "reservation".event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "reservation".reservation_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE "reservation"');
    }
}
