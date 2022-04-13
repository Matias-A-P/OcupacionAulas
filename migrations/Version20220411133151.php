<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411133151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocupacion ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ocupacion ADD CONSTRAINT FK_C6DC246A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C6DC246A76ED395 ON ocupacion (user_id)');
        $this->addSql('ALTER TABLE user ADD nombre VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocupacion DROP FOREIGN KEY FK_C6DC246A76ED395');
        $this->addSql('DROP INDEX IDX_C6DC246A76ED395 ON ocupacion');
        $this->addSql('ALTER TABLE ocupacion DROP user_id');
        $this->addSql('ALTER TABLE user DROP nombre');
    }
}
