<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317160937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE edificios_pisos (id INT AUTO_INCREMENT NOT NULL, id_edificio_id INT NOT NULL, piso VARCHAR(50) NOT NULL, INDEX IDX_772ADAF09277E621 (id_edificio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE edificios_pisos ADD CONSTRAINT FK_772ADAF09277E621 FOREIGN KEY (id_edificio_id) REFERENCES edificios (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE edificios_pisos');
    }
}
