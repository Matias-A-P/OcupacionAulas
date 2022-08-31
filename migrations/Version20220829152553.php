<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220829152553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_edificios (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, edificio_id INT NOT NULL, INDEX IDX_B5E1FC01A76ED395 (user_id), INDEX IDX_B5E1FC018A652BD6 (edificio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_edificios ADD CONSTRAINT FK_B5E1FC01A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_edificios ADD CONSTRAINT FK_B5E1FC018A652BD6 FOREIGN KEY (edificio_id) REFERENCES edificios (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_edificios DROP FOREIGN KEY FK_B5E1FC01A76ED395');
        $this->addSql('ALTER TABLE user_edificios DROP FOREIGN KEY FK_B5E1FC018A652BD6');
        $this->addSql('DROP TABLE user_edificios');
    }
}
