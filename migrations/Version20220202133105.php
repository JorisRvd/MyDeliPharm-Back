<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202133105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, street VARCHAR(255) NOT NULL, postcode VARCHAR(5) NOT NULL, city VARCHAR(50) NOT NULL, INDEX IDX_D4E6F81A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dispensary (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, status SMALLINT NOT NULL, other VARCHAR(255) DEFAULT NULL, opening_hours VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_7F859697F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, location VARCHAR(255) NOT NULL, vehicule VARCHAR(50) NOT NULL, status SMALLINT NOT NULL, profil_pic VARCHAR(2048) NOT NULL, UNIQUE INDEX UNIQ_11667CD9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, driver_id INT DEFAULT NULL, pharmacist_id INT DEFAULT NULL, prescription_image VARCHAR(255) DEFAULT NULL, safety_code INT NOT NULL, status SMALLINT NOT NULL, INDEX IDX_F52993986B899279 (patient_id), INDEX IDX_F5299398C3423909 (driver_id), INDEX IDX_F52993981FDF4367 (pharmacist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, dispensary_id INT DEFAULT NULL, weight INT DEFAULT NULL, age INT DEFAULT NULL, vital_card_number INT DEFAULT NULL, mutuelle_number INT DEFAULT NULL, other VARCHAR(255) DEFAULT NULL, status SMALLINT DEFAULT NULL, vital_card_file VARCHAR(2048) DEFAULT NULL, mutuelle_file VARCHAR(2048) DEFAULT NULL, UNIQUE INDEX UNIQ_1ADAD7EBA76ED395 (user_id), INDEX IDX_1ADAD7EB24D43109 (dispensary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacist (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, dispensary_id INT DEFAULT NULL, rpps_number INT NOT NULL, status SMALLINT NOT NULL, profil_pic VARCHAR(2048) DEFAULT NULL, UNIQUE INDEX UNIQ_B819DA5EA76ED395 (user_id), INDEX IDX_B819DA5E24D43109 (dispensary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, phone_number VARCHAR(15) NOT NULL, is_admin TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dispensary ADD CONSTRAINT FK_7F859697F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993981FDF4367 FOREIGN KEY (pharmacist_id) REFERENCES pharmacist (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB24D43109 FOREIGN KEY (dispensary_id) REFERENCES dispensary (id)');
        $this->addSql('ALTER TABLE pharmacist ADD CONSTRAINT FK_B819DA5EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pharmacist ADD CONSTRAINT FK_B819DA5E24D43109 FOREIGN KEY (dispensary_id) REFERENCES dispensary (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dispensary DROP FOREIGN KEY FK_7F859697F5B7AF75');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB24D43109');
        $this->addSql('ALTER TABLE pharmacist DROP FOREIGN KEY FK_B819DA5E24D43109');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398C3423909');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986B899279');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993981FDF4367');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9A76ED395');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA76ED395');
        $this->addSql('ALTER TABLE pharmacist DROP FOREIGN KEY FK_B819DA5EA76ED395');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE dispensary');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE pharmacist');
        $this->addSql('DROP TABLE user');
    }
}
