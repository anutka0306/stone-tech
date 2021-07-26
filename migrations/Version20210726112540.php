<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210726112540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stone_product ADD parent_id INT NOT NULL');
        $this->addSql('ALTER TABLE stone_product ADD CONSTRAINT FK_F7D0442C727ACA70 FOREIGN KEY (parent_id) REFERENCES stone_catalog (id)');
        $this->addSql('CREATE INDEX IDX_F7D0442C727ACA70 ON stone_product (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stone_product DROP FOREIGN KEY FK_F7D0442C727ACA70');
        $this->addSql('DROP INDEX IDX_F7D0442C727ACA70 ON stone_product');
        $this->addSql('ALTER TABLE stone_product DROP parent_id');
    }
}
