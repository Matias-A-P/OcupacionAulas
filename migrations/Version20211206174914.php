<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206174914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aulas ADD id_edificio_id INT NOT NULL');
        $this->addSql('ALTER TABLE aulas ADD CONSTRAINT FK_CAB6B16A9277E621 FOREIGN KEY (id_edificio_id) REFERENCES edificios (id)');
        $this->addSql('CREATE INDEX IDX_CAB6B16A9277E621 ON aulas (id_edificio_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aulas DROP FOREIGN KEY FK_CAB6B16A9277E621');
        $this->addSql('DROP INDEX IDX_CAB6B16A9277E621 ON aulas');
        $this->addSql('ALTER TABLE aulas DROP id_edificio_id');
    }
}
