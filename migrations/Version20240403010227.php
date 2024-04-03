<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403010227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appel_offre (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, prix_initial DOUBLE PRECISION DEFAULT NULL, prix_final DOUBLE PRECISION DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, etat_payment VARCHAR(255) DEFAULT NULL, INDEX IDX_BC56FD47A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, appel_offre_id INT DEFAULT NULL, user_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, date_soumission DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, etat_payment VARCHAR(255) DEFAULT NULL, date_payment DATETIME DEFAULT NULL, INDEX IDX_AF86866F308E35F8 (appel_offre_id), INDEX IDX_AF86866FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stocks (id INT AUTO_INCREMENT NOT NULL, appel_offre_id INT DEFAULT NULL, user_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, prix_unit DOUBLE PRECISION DEFAULT NULL, quantite DOUBLE PRECISION DEFAULT NULL, unite VARCHAR(255) DEFAULT NULL, date_ajout DATETIME DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, INDEX IDX_56F79805308E35F8 (appel_offre_id), INDEX IDX_56F79805A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, verifcode VARCHAR(255) DEFAULT NULL, cin VARCHAR(255) DEFAULT NULL, mat_fiscal VARCHAR(255) DEFAULT NULL, rib VARCHAR(255) DEFAULT NULL, nbre_point INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, user_name VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appel_offre ADD CONSTRAINT FK_BC56FD47A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F308E35F8 FOREIGN KEY (appel_offre_id) REFERENCES appel_offre (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F79805308E35F8 FOREIGN KEY (appel_offre_id) REFERENCES appel_offre (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F79805A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_offre DROP FOREIGN KEY FK_BC56FD47A76ED395');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F308E35F8');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FA76ED395');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F79805308E35F8');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F79805A76ED395');
        $this->addSql('DROP TABLE appel_offre');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE stocks');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
