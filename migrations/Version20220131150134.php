<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131150134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocupacion ADD id_area_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ocupacion ADD CONSTRAINT FK_C6DC2466392E9EC FOREIGN KEY (id_area_id) REFERENCES areas (id)');
        $this->addSql('CREATE INDEX IDX_C6DC2466392E9EC ON ocupacion (id_area_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocupacion DROP FOREIGN KEY FK_C6DC2466392E9EC');
        $this->addSql('DROP INDEX IDX_C6DC2466392E9EC ON ocupacion');
        $this->addSql('ALTER TABLE ocupacion DROP id_area_id');
    }
}
