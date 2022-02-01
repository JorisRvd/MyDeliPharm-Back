<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131153821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dispensary CHANGE address_id address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE prescription_file prescription VARCHAR(2048) NOT NULL');
        $this->addSql('ALTER TABLE patient ADD orders_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES `order` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EBCFFE9AD6 ON patient (orders_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dispensary CHANGE address_id address_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE prescription prescription_file VARCHAR(2048) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBCFFE9AD6');
        $this->addSql('DROP INDEX UNIQ_1ADAD7EBCFFE9AD6 ON patient');
        $this->addSql('ALTER TABLE patient DROP orders_id');
    }
}
