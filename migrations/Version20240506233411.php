<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506233411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_offre ADD date_fin DATETIME DEFAULT NULL, DROP daate_fin, CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE prix_initial prix_initial DOUBLE PRECISION DEFAULT NULL, CHANGE date_debut date_debut DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0786A81FB');
        $this->addSql('DROP INDEX IDX_8F91ABF0786A81FB ON avis');
        $this->addSql('ALTER TABLE avis CHANGE iduser_id iduser INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF05E5C27E9 FOREIGN KEY (iduser) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF05E5C27E9 ON avis (iduser)');
        $this->addSql('ALTER TABLE offre CHANGE description description LONGTEXT DEFAULT NULL, CHANGE date_soumissiom date_soumission DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('DROP INDEX IDX_CE606404A76ED395 ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD submissionDate DATETIME DEFAULT NULL, ADD updateDate DATETIME DEFAULT NULL, DROP submission_date, DROP update_date, CHANGE description description TEXT DEFAULT NULL, CHANGE status status VARCHAR(50) DEFAULT \'en coure de traitement\', CHANGE reclaim_type reclaimType VARCHAR(255) DEFAULT NULL, CHANGE user_id userId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640464B64DCC FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CE60640464B64DCC ON reclamation (userId)');
        $this->addSql('ALTER TABLE stocks CHANGE appel_offre_id appel_offre_id INT NOT NULL, CHANGE date_ajout date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE update_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user CHANGE adresse adresse VARCHAR(100) NOT NULL, CHANGE cin cin VARCHAR(8) NOT NULL, CHANGE telephone telephone VARCHAR(12) NOT NULL, CHANGE rib rib VARCHAR(20) NOT NULL, CHANGE mat_fiscal mat_fiscal VARCHAR(50) NOT NULL, CHANGE nbre_point nbre_point INT NOT NULL, CHANGE image_name image_name VARCHAR(255) NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE email email VARCHAR(50) NOT NULL, CHANGE is_verified is_verified TINYINT(1) NOT NULL, CHANGE nom nom VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_offre ADD daate_fin DATETIME NOT NULL, DROP date_fin, CHANGE titre titre VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE prix_initial prix_initial DOUBLE PRECISION NOT NULL, CHANGE date_debut date_debut DATETIME NOT NULL');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF05E5C27E9');
        $this->addSql('DROP INDEX IDX_8F91ABF05E5C27E9 ON avis');
        $this->addSql('ALTER TABLE avis CHANGE iduser iduser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0786A81FB FOREIGN KEY (iduser_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0786A81FB ON avis (iduser_id)');
        $this->addSql('ALTER TABLE offre CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE date_soumission date_soumissiom DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640464B64DCC');
        $this->addSql('DROP INDEX IDX_CE60640464B64DCC ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD submission_date DATETIME DEFAULT NULL, ADD update_date DATETIME DEFAULT NULL, DROP submissionDate, DROP updateDate, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(50) DEFAULT NULL, CHANGE userId user_id INT DEFAULT NULL, CHANGE reclaimType reclaim_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CE606404A76ED395 ON reclamation (user_id)');
        $this->addSql('ALTER TABLE stocks CHANGE appel_offre_id appel_offre_id INT DEFAULT NULL, CHANGE date_ajout date_ajout DATETIME DEFAULT NULL, CHANGE updated_at update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user CHANGE adresse adresse VARCHAR(100) DEFAULT NULL, CHANGE cin cin VARCHAR(8) DEFAULT NULL, CHANGE telephone telephone VARCHAR(12) DEFAULT NULL, CHANGE rib rib VARCHAR(20) DEFAULT NULL, CHANGE mat_fiscal mat_fiscal VARCHAR(50) DEFAULT NULL, CHANGE nbre_point nbre_point INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE email email VARCHAR(50) DEFAULT NULL, CHANGE is_verified is_verified TINYINT(1) DEFAULT NULL, CHANGE nom nom VARCHAR(20) DEFAULT NULL');
    }
}
