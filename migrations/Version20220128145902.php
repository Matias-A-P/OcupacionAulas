<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128145902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE areas (id INT AUTO_INCREMENT NOT NULL, area VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catedras ADD area_id INT NOT NULL');
        $this->addSql('ALTER TABLE catedras ADD CONSTRAINT FK_813D2E80BD0F409C FOREIGN KEY (area_id) REFERENCES areas (id)');
        $this->addSql('CREATE INDEX IDX_813D2E80BD0F409C ON catedras (area_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catedras DROP FOREIGN KEY FK_813D2E80BD0F409C');
        $this->addSql('DROP TABLE areas');
        $this->addSql('DROP INDEX IDX_813D2E80BD0F409C ON catedras');
        $this->addSql('ALTER TABLE catedras DROP area_id');
    }
}
