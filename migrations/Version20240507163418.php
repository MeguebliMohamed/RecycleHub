<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507163418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE prenom prenom VARCHAR(50) DEFAULT NULL, CHANGE adresse adresse VARCHAR(100) DEFAULT NULL, CHANGE cin cin VARCHAR(8) DEFAULT NULL, CHANGE telephone telephone VARCHAR(12) DEFAULT NULL, CHANGE mat_fiscal mat_fiscal VARCHAR(50) DEFAULT NULL, CHANGE nbre_point nbre_point INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE email email VARCHAR(50) DEFAULT NULL, CHANGE is_verified is_verified TINYINT(1) DEFAULT NULL, CHANGE nom nom VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE prenom prenom VARCHAR(50) NOT NULL, CHANGE adresse adresse VARCHAR(100) NOT NULL, CHANGE cin cin VARCHAR(8) NOT NULL, CHANGE telephone telephone VARCHAR(12) NOT NULL, CHANGE mat_fiscal mat_fiscal VARCHAR(50) NOT NULL, CHANGE nbre_point nbre_point INT NOT NULL, CHANGE image_name image_name VARCHAR(255) NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE email email VARCHAR(50) NOT NULL, CHANGE is_verified is_verified TINYINT(1) NOT NULL, CHANGE nom nom VARCHAR(20) NOT NULL');
    }
}
