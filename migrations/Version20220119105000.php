<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119105000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F81A76ED395 ON address (user_id)');
        $this->addSql('ALTER TABLE dispensary ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE dispensary ADD CONSTRAINT FK_7F859697F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7F859697F5B7AF75 ON dispensary (address_id)');
        $this->addSql('ALTER TABLE driver ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD9A76ED395 ON driver (user_id)');
        $this->addSql('ALTER TABLE `order` ADD patient_id INT DEFAULT NULL, ADD driver_id INT DEFAULT NULL, ADD pharmacist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993981FDF4367 FOREIGN KEY (pharmacist_id) REFERENCES pharmacist (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993986B899279 ON `order` (patient_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398C3423909 ON `order` (driver_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993981FDF4367 ON `order` (pharmacist_id)');
        $this->addSql('ALTER TABLE patient ADD user_id INT NOT NULL, ADD dispensary_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB24D43109 FOREIGN KEY (dispensary_id) REFERENCES dispensary (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EBA76ED395 ON patient (user_id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EB24D43109 ON patient (dispensary_id)');
        $this->addSql('ALTER TABLE pharmacist ADD user_id INT NOT NULL, ADD dispensary_id INT NOT NULL');
        $this->addSql('ALTER TABLE pharmacist ADD CONSTRAINT FK_B819DA5EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pharmacist ADD CONSTRAINT FK_B819DA5E24D43109 FOREIGN KEY (dispensary_id) REFERENCES dispensary (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B819DA5EA76ED395 ON pharmacist (user_id)');
        $this->addSql('CREATE INDEX IDX_B819DA5E24D43109 ON pharmacist (dispensary_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('DROP INDEX IDX_D4E6F81A76ED395 ON address');
        $this->addSql('ALTER TABLE address DROP user_id');
        $this->addSql('ALTER TABLE dispensary DROP FOREIGN KEY FK_7F859697F5B7AF75');
        $this->addSql('DROP INDEX UNIQ_7F859697F5B7AF75 ON dispensary');
        $this->addSql('ALTER TABLE dispensary DROP address_id');
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9A76ED395');
        $this->addSql('DROP INDEX UNIQ_11667CD9A76ED395 ON driver');
        $this->addSql('ALTER TABLE driver DROP user_id');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986B899279');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398C3423909');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993981FDF4367');
        $this->addSql('DROP INDEX UNIQ_F52993986B899279 ON `order`');
        $this->addSql('DROP INDEX UNIQ_F5299398C3423909 ON `order`');
        $this->addSql('DROP INDEX UNIQ_F52993981FDF4367 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP patient_id, DROP driver_id, DROP pharmacist_id');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA76ED395');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB24D43109');
        $this->addSql('DROP INDEX UNIQ_1ADAD7EBA76ED395 ON patient');
        $this->addSql('DROP INDEX IDX_1ADAD7EB24D43109 ON patient');
        $this->addSql('ALTER TABLE patient DROP user_id, DROP dispensary_id');
        $this->addSql('ALTER TABLE pharmacist DROP FOREIGN KEY FK_B819DA5EA76ED395');
        $this->addSql('ALTER TABLE pharmacist DROP FOREIGN KEY FK_B819DA5E24D43109');
        $this->addSql('DROP INDEX UNIQ_B819DA5EA76ED395 ON pharmacist');
        $this->addSql('DROP INDEX IDX_B819DA5E24D43109 ON pharmacist');
        $this->addSql('ALTER TABLE pharmacist DROP user_id, DROP dispensary_id');
    }
}
