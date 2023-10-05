<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005193702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE OR REPLACE FUNCTION notify_about_new_event() RETURNS TRIGGER AS $$
        BEGIN
            PERFORM pg_notify(\'event_store_new_event\', concat(\'{\"eventId\":\', NEW.id::text ,\'}\'));
            RETURN NEW;
        END;
    $$ LANGUAGE plpgsql;');

    $this->addSql('DROP TRIGGER IF EXISTS notify_about_new_event ON event_store;');
    $this->addSql('CREATE TRIGGER notify_about_new_event AFTER INSERT OR UPDATE ON event_store FOR EACH ROW EXECUTE PROCEDURE notify_about_new_event();');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
