<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107142853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aulas (id INT AUTO_INCREMENT NOT NULL, id_edificio_id INT NOT NULL, aula VARCHAR(100) NOT NULL, capacidad INT DEFAULT NULL, INDEX IDX_CAB6B16A9277E621 (id_edificio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catedras (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edificios (id INT AUTO_INCREMENT NOT NULL, edificio VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ocupacion (id INT AUTO_INCREMENT NOT NULL, id_aula_id INT NOT NULL, id_catedra_id INT DEFAULT NULL, fecha DATE NOT NULL, hora_inicio TIME NOT NULL, hora_fin TIME NOT NULL, comision INT NOT NULL, INDEX IDX_C6DC2467387BB25 (id_aula_id), INDEX IDX_C6DC246CCB14CBA (id_catedra_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aulas ADD CONSTRAINT FK_CAB6B16A9277E621 FOREIGN KEY (id_edificio_id) REFERENCES edificios (id)');
        $this->addSql('ALTER TABLE ocupacion ADD CONSTRAINT FK_C6DC2467387BB25 FOREIGN KEY (id_aula_id) REFERENCES aulas (id)');
        $this->addSql('ALTER TABLE ocupacion ADD CONSTRAINT FK_C6DC246CCB14CBA FOREIGN KEY (id_catedra_id) REFERENCES catedras (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ocupacion DROP FOREIGN KEY FK_C6DC2467387BB25');
        $this->addSql('ALTER TABLE ocupacion DROP FOREIGN KEY FK_C6DC246CCB14CBA');
        $this->addSql('ALTER TABLE aulas DROP FOREIGN KEY FK_CAB6B16A9277E621');
        $this->addSql('DROP TABLE aulas');
        $this->addSql('DROP TABLE catedras');
        $this->addSql('DROP TABLE edificios');
        $this->addSql('DROP TABLE ocupacion');
    }
}
