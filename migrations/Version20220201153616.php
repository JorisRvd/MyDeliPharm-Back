<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201153616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP INDEX UNIQ_F52993986B899279, ADD INDEX IDX_F52993986B899279 (patient_id)');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBCFFE9AD6');
        $this->addSql('DROP INDEX UNIQ_1ADAD7EBCFFE9AD6 ON patient');
        $this->addSql('ALTER TABLE patient DROP orders_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP INDEX IDX_F52993986B899279, ADD UNIQUE INDEX UNIQ_F52993986B899279 (patient_id)');
        $this->addSql('ALTER TABLE patient ADD orders_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES `order` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EBCFFE9AD6 ON patient (orders_id)');
    }
}
