<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506200521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515FB88E14F');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515DCD6110');
        $this->addSql('ALTER TABLE appelsoffres DROP FOREIGN KEY FK_FB171E2D1614F182');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF05E5C27E9');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC3544C6616FD3');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC3544D75B982');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640464B64DCC');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660FE6E88D7');
        $this->addSql('ALTER TABLE usercadeaux DROP FOREIGN KEY FK_5D32AA51FB88E14F');
        $this->addSql('ALTER TABLE usercadeaux DROP FOREIGN KEY FK_5D32AA51D9D5ED84');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE appelsoffres');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE cadeaux');
        $this->addSql('DROP TABLE offres');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE usercadeaux');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD image_name VARCHAR(255) NOT NULL, ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD is_verified TINYINT(1) NOT NULL, DROP status, DROP role, DROP verifcode, DROP user_name, DROP image, CHANGE nom nom VARCHAR(20) NOT NULL, CHANGE prenom prenom VARCHAR(50) NOT NULL, CHANGE adresse adresse VARCHAR(100) NOT NULL, CHANGE telephone telephone VARCHAR(12) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE cin cin VARCHAR(8) NOT NULL, CHANGE mat_fiscal mat_fiscal VARCHAR(50) NOT NULL, CHANGE rib rib VARCHAR(20) NOT NULL, CHANGE nbre_point nbre_point INT NOT NULL, CHANGE Email email VARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, date_parcours DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, date_fin DATETIME DEFAULT NULL, itineraire INT DEFAULT NULL, INDEX IDX_B8755515FB88E14F (utilisateur_id), INDEX IDX_B8755515DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE appelsoffres (id INT AUTO_INCREMENT NOT NULL, collecteur_id INT DEFAULT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, prixInitial DOUBLE PRECISION NOT NULL, prixFinal DOUBLE PRECISION NOT NULL, date_debut DATETIME DEFAULT CURRENT_TIMESTAMP, date_fin DATETIME DEFAULT NULL, etat VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'En cours\' NOT NULL COLLATE `utf8mb4_unicode_ci`, etatPayment VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_FB171E2D1614F182 (collecteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, note INT NOT NULL, avi VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8F91ABF05E5C27E9 (iduser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE cadeaux (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, prix DOUBLE PRECISION NOT NULL, stock INT NOT NULL, disponible TINYINT(1) NOT NULL, date_disponibilite DATE DEFAULT NULL, categorie VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, imageUrl VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offres (idsociete INT DEFAULT NULL, idOffre INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION DEFAULT NULL, dateSoumission DATETIME DEFAULT CURRENT_TIMESTAMP, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, etat VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'En cours\' COLLATE `utf8mb4_unicode_ci`, etatPayment VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, datePayment DATETIME DEFAULT CURRENT_TIMESTAMP, idAppelOffre INT DEFAULT NULL, INDEX IDX_C6AC3544D75B982 (idAppelOffre), INDEX IDX_C6AC3544C6616FD3 (idsociete), PRIMARY KEY(idOffre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, reclaimType VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, status VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'en coure de traitement\' COLLATE `utf8mb4_unicode_ci`, submissionDate DATETIME DEFAULT NULL, updateDate DATETIME DEFAULT NULL, userId INT DEFAULT NULL, INDEX IDX_CE60640464B64DCC (userId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, prixUnit NUMERIC(10, 2) DEFAULT NULL, quantite NUMERIC(10, 2) DEFAULT NULL, unite VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, dateAjout DATETIME DEFAULT CURRENT_TIMESTAMP, etat VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, imageUrl VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, idAppelOffre INT DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, idUser INT DEFAULT NULL, INDEX IDX_4B365660FE6E88D7 (idUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE usercadeaux (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, cadeau_id INT DEFAULT NULL, quantite_achetee INT DEFAULT NULL, date_achat DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_5D32AA51FB88E14F (utilisateur_id), INDEX IDX_5D32AA51D9D5ED84 (cadeau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE appelsoffres ADD CONSTRAINT FK_FB171E2D1614F182 FOREIGN KEY (collecteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF05E5C27E9 FOREIGN KEY (iduser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC3544C6616FD3 FOREIGN KEY (idsociete) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC3544D75B982 FOREIGN KEY (idAppelOffre) REFERENCES appelsoffres (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640464B64DCC FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660FE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE usercadeaux ADD CONSTRAINT FK_5D32AA51FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE usercadeaux ADD CONSTRAINT FK_5D32AA51D9D5ED84 FOREIGN KEY (cadeau_id) REFERENCES cadeaux (id)');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user ADD status VARCHAR(20) DEFAULT NULL, ADD role VARCHAR(20) DEFAULT NULL, ADD verifcode VARCHAR(10) DEFAULT NULL, ADD user_name VARCHAR(50) DEFAULT NULL, ADD image VARCHAR(60) DEFAULT NULL, DROP username, DROP roles, DROP image_name, DROP updated_at, DROP is_verified, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE cin cin VARCHAR(20) DEFAULT NULL, CHANGE telephone telephone VARCHAR(20) DEFAULT NULL, CHANGE rib rib VARCHAR(20) DEFAULT NULL, CHANGE mat_fiscal mat_fiscal VARCHAR(20) DEFAULT NULL, CHANGE nbre_point nbre_point INT DEFAULT NULL, CHANGE email Email VARCHAR(255) DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL');
    }
}
