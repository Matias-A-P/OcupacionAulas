<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220307161212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE areas (id INT AUTO_INCREMENT NOT NULL, area VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aulas (id INT AUTO_INCREMENT NOT NULL, id_edificio_id INT NOT NULL, aula VARCHAR(100) NOT NULL, capacidad INT DEFAULT NULL, INDEX IDX_CAB6B16A9277E621 (id_edificio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catedras (id INT AUTO_INCREMENT NOT NULL, area_id INT NOT NULL, nombre VARCHAR(100) NOT NULL, INDEX IDX_813D2E80BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edificios (id INT AUTO_INCREMENT NOT NULL, edificio VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ocupacion (id INT AUTO_INCREMENT NOT NULL, id_aula_id INT NOT NULL, id_catedra_id INT DEFAULT NULL, id_area_id INT DEFAULT NULL, fecha DATE NOT NULL, hora_inicio TIME NOT NULL, hora_fin TIME NOT NULL, comision INT NOT NULL, rep_semanal TINYINT(1) DEFAULT 0 NOT NULL, rep_fecha_fin DATE DEFAULT NULL, rep_id_padre INT DEFAULT 0, observaciones VARCHAR(255) DEFAULT NULL, INDEX IDX_C6DC2467387BB25 (id_aula_id), INDEX IDX_C6DC246CCB14CBA (id_catedra_id), INDEX IDX_C6DC2466392E9EC (id_area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, dni INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6497F8F253B (dni), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aulas ADD CONSTRAINT FK_CAB6B16A9277E621 FOREIGN KEY (id_edificio_id) REFERENCES edificios (id)');
        $this->addSql('ALTER TABLE catedras ADD CONSTRAINT FK_813D2E80BD0F409C FOREIGN KEY (area_id) REFERENCES areas (id)');
        $this->addSql('ALTER TABLE ocupacion ADD CONSTRAINT FK_C6DC2467387BB25 FOREIGN KEY (id_aula_id) REFERENCES aulas (id)');
        $this->addSql('ALTER TABLE ocupacion ADD CONSTRAINT FK_C6DC246CCB14CBA FOREIGN KEY (id_catedra_id) REFERENCES catedras (id)');
        $this->addSql('ALTER TABLE ocupacion ADD CONSTRAINT FK_C6DC2466392E9EC FOREIGN KEY (id_area_id) REFERENCES areas (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catedras DROP FOREIGN KEY FK_813D2E80BD0F409C');
        $this->addSql('ALTER TABLE ocupacion DROP FOREIGN KEY FK_C6DC2466392E9EC');
        $this->addSql('ALTER TABLE ocupacion DROP FOREIGN KEY FK_C6DC2467387BB25');
        $this->addSql('ALTER TABLE ocupacion DROP FOREIGN KEY FK_C6DC246CCB14CBA');
        $this->addSql('ALTER TABLE aulas DROP FOREIGN KEY FK_CAB6B16A9277E621');
        $this->addSql('DROP TABLE areas');
        $this->addSql('DROP TABLE aulas');
        $this->addSql('DROP TABLE catedras');
        $this->addSql('DROP TABLE edificios');
        $this->addSql('DROP TABLE ocupacion');
        $this->addSql('DROP TABLE user');
    }
}
