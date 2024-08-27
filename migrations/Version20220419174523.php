<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419174523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id_abonnement INT AUTO_INCREMENT NOT NULL, type_abonnenemt VARCHAR(45) NOT NULL, date_debut_abonnement VARCHAR(45) NOT NULL, date_fin_abonnement VARCHAR(45) NOT NULL, prix VARCHAR(45) DEFAULT NULL, PRIMARY KEY(id_abonnement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (idAvis INT AUTO_INCREMENT NOT NULL, rating INT NOT NULL, descriptionAvis VARCHAR(255) NOT NULL, idUser INT DEFAULT NULL, INDEX IDX_8F91ABF0FE6E88D7 (idUser), PRIMARY KEY(idAvis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE espaceprestatairee (idprest INT AUTO_INCREMENT NOT NULL, nomSociete VARCHAR(20) NOT NULL, numSociete VARCHAR(20) NOT NULL, FaxSociete VARCHAR(20) NOT NULL, catSociete VARCHAR(20) NOT NULL, typeSociete VARCHAR(20) NOT NULL, logo VARCHAR(255) NOT NULL, Url VARCHAR(255) NOT NULL, idplan INT DEFAULT NULL, idservice INT NOT NULL, iduser INT DEFAULT NULL, INDEX fk_serv (idservice), PRIMARY KEY(idprest)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE packs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, description VARCHAR(500) NOT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, iduser INT DEFAULT NULL, promo INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (idPlan INT AUTO_INCREMENT NOT NULL, dateplan VARCHAR(20) NOT NULL, etatplan VARCHAR(11) NOT NULL, idservice INT NOT NULL, INDEX fk_ser (idservice), PRIMARY KEY(idPlan)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (idReclamation INT AUTO_INCREMENT NOT NULL, dateReclamtion DATE NOT NULL, descriptionReclamtion VARCHAR(255) NOT NULL, imageReclamtion VARCHAR(255) NOT NULL, etatreclamtion VARCHAR(255) NOT NULL, idUser INT DEFAULT NULL, INDEX IDX_CE606404FE6E88D7 (idUser), PRIMARY KEY(idReclamation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE res_ligne_pack (id INT AUTO_INCREMENT NOT NULL, idrespack INT DEFAULT NULL, idpack INT DEFAULT NULL, etat VARCHAR(10) NOT NULL, numlignepack VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE res_ligne_service (id INT AUTO_INCREMENT NOT NULL, idservice INT DEFAULT NULL, etat VARCHAR(10) DEFAULT NULL, numligneservice VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservationpack (id INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, idpack INT DEFAULT NULL, date VARCHAR(10) DEFAULT NULL, heuredeb VARCHAR(10) DEFAULT NULL, heurefin VARCHAR(10) DEFAULT NULL, prixrespack DOUBLE PRECISION NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_8FB76A065E5C27E9 (iduser), INDEX IDX_8FB76A064411AF83 (idpack), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservationservice (id INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, idservice INT DEFAULT NULL, date VARCHAR(10) DEFAULT NULL, heuredeb VARCHAR(10) DEFAULT NULL, heurefin VARCHAR(10) DEFAULT NULL, prixresserv DOUBLE PRECISION DEFAULT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_505F34CA5E5C27E9 (iduser), INDEX IDX_505F34CA3E99C8BC (idservice), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, description VARCHAR(500) NOT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, categorie VARCHAR(100) NOT NULL, iduser INT DEFAULT NULL, idPack INT DEFAULT NULL, INDEX fk_p (idPack), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, password VARCHAR(50) DEFAULT NULL, tel INT DEFAULT NULL, role VARCHAR(20) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0FE6E88D7 FOREIGN KEY (idUser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404FE6E88D7 FOREIGN KEY (idUser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservationpack ADD CONSTRAINT FK_8FB76A065E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservationpack ADD CONSTRAINT FK_8FB76A064411AF83 FOREIGN KEY (idpack) REFERENCES packs (id)');
        $this->addSql('ALTER TABLE reservationservice ADD CONSTRAINT FK_505F34CA5E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservationservice ADD CONSTRAINT FK_505F34CA3E99C8BC FOREIGN KEY (idservice) REFERENCES services (id)');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E169E42300BD FOREIGN KEY (idPack) REFERENCES packs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservationpack DROP FOREIGN KEY FK_8FB76A064411AF83');
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E169E42300BD');
        $this->addSql('ALTER TABLE reservationservice DROP FOREIGN KEY FK_505F34CA3E99C8BC');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0FE6E88D7');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404FE6E88D7');
        $this->addSql('ALTER TABLE reservationpack DROP FOREIGN KEY FK_8FB76A065E5C27E9');
        $this->addSql('ALTER TABLE reservationservice DROP FOREIGN KEY FK_505F34CA5E5C27E9');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE espaceprestatairee');
        $this->addSql('DROP TABLE packs');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE res_ligne_pack');
        $this->addSql('DROP TABLE res_ligne_service');
        $this->addSql('DROP TABLE reservationpack');
        $this->addSql('DROP TABLE reservationservice');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE utilisateur');
    }
}
