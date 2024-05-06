<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506224825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appel_offre (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix_initial DOUBLE PRECISION NOT NULL, prix_final DOUBLE PRECISION DEFAULT NULL, date_debut DATETIME NOT NULL, daate_fin DATETIME NOT NULL, etat VARCHAR(255) DEFAULT NULL, etat_payment VARCHAR(255) DEFAULT NULL, INDEX IDX_BC56FD47A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, iduser_id INT DEFAULT NULL, note INT NOT NULL, avi VARCHAR(255) NOT NULL, INDEX IDX_8F91ABF0786A81FB (iduser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, appel_offre_id INT DEFAULT NULL, user_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, date_soumissiom DATETIME DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, etat_payment VARCHAR(255) DEFAULT NULL, date_payment DATETIME DEFAULT NULL, INDEX IDX_AF86866F308E35F8 (appel_offre_id), INDEX IDX_AF86866FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, reclaim_type VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, submission_date DATETIME DEFAULT NULL, update_date DATETIME DEFAULT NULL, INDEX IDX_CE606404A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stocks (id INT AUTO_INCREMENT NOT NULL, appel_offre_id INT DEFAULT NULL, user_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, prix_unit DOUBLE PRECISION DEFAULT NULL, quantite DOUBLE PRECISION DEFAULT NULL, unite VARCHAR(255) DEFAULT NULL, date_ajout DATETIME DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, INDEX IDX_56F79805308E35F8 (appel_offre_id), INDEX IDX_56F79805A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, prenom VARCHAR(50) NOT NULL, adresse VARCHAR(100) NOT NULL, cin VARCHAR(8) NOT NULL, telephone VARCHAR(12) NOT NULL, rib VARCHAR(20) NOT NULL, mat_fiscal VARCHAR(50) NOT NULL, nbre_point INT NOT NULL, image_name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', email VARCHAR(50) NOT NULL, is_verified TINYINT(1) NOT NULL, nom VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appel_offre ADD CONSTRAINT FK_BC56FD47A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0786A81FB FOREIGN KEY (iduser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F308E35F8 FOREIGN KEY (appel_offre_id) REFERENCES appel_offre (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F79805308E35F8 FOREIGN KEY (appel_offre_id) REFERENCES appel_offre (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F79805A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_offre DROP FOREIGN KEY FK_BC56FD47A76ED395');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0786A81FB');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F308E35F8');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FA76ED395');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F79805308E35F8');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F79805A76ED395');
        $this->addSql('DROP TABLE appel_offre');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE stocks');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
