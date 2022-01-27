<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127114050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient CHANGE weight weight INT NOT NULL, CHANGE age age INT NOT NULL, CHANGE status status SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE pharmacist CHANGE dispensary_id dispensary_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient CHANGE weight weight INT DEFAULT NULL, CHANGE age age INT DEFAULT NULL, CHANGE status status SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE pharmacist CHANGE dispensary_id dispensary_id INT NOT NULL');
    }
}
