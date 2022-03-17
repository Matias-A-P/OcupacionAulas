<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317163641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aulas ADD id_piso_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE aulas ADD CONSTRAINT FK_CAB6B16AC45599DF FOREIGN KEY (id_piso_id) REFERENCES edificios_pisos (id)');
        $this->addSql('CREATE INDEX IDX_CAB6B16AC45599DF ON aulas (id_piso_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aulas DROP FOREIGN KEY FK_CAB6B16AC45599DF');
        $this->addSql('DROP INDEX IDX_CAB6B16AC45599DF ON aulas');
        $this->addSql('ALTER TABLE aulas DROP id_piso_id');
    }
}
