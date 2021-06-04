<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602180513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, page_type VARCHAR(50) NOT NULL, path VARCHAR(100) NOT NULL, name VARCHAR(150) NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description LONGTEXT DEFAULT NULL, parent INT DEFAULT NULL, seo_text LONGTEXT DEFAULT NULL, card_title VARCHAR(255) DEFAULT NULL, card_description LONGTEXT DEFAULT NULL, card_price INT DEFAULT NULL, card_measure INT DEFAULT NULL, top_menu TINYINT(1) NOT NULL, index_menu TINYINT(1) NOT NULL, thumb_img VARCHAR(255) DEFAULT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE content');
    }
}
