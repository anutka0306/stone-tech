<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210617092410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content ADD category_id_id INT DEFAULT NULL, CHANGE updated updated DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A99777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEC530A99777D11E ON content (category_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A99777D11E');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX UNIQ_FEC530A99777D11E ON content');
        $this->addSql('ALTER TABLE content DROP category_id_id, CHANGE updated updated DATETIME DEFAULT CURRENT_TIMESTAMP');
    }
}
