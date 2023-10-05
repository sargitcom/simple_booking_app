<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005131703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE event_store_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "event_store" (id BIGINT NOT NULL, event_id UUID NOT NULL, aggregate_id UUID NOT NULL, version INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, domain_event_name VARCHAR(512) NOT NULL, domain_event_body JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "event_store".event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "event_store".aggregate_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "event_store".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "last_event_store_projection_event" (id BIGINT NOT NULL, event_id TEXT NOT NULL, projection_name TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, roles TEXT NOT NULL, is_verified BOOLEAN NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(320) NOT NULL, password VARCHAR(4096) NOT NULL, language_code VARCHAR(2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE user_registration_confirmation (id BIGINT NOT NULL, confirmation_id UUID NOT NULL, user_id UUID NOT NULL, confirmation_code VARCHAR(255) NOT NULL, account_activated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E26904706BACE54E ON user_registration_confirmation (confirmation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E2690470A76ED395 ON user_registration_confirmation (user_id)');
        $this->addSql('COMMENT ON COLUMN user_registration_confirmation.confirmation_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_registration_confirmation.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE event_store_seq CASCADE');
        $this->addSql('DROP TABLE "event_store"');
        $this->addSql('DROP TABLE "last_event_store_projection_event"');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_registration_confirmation');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
