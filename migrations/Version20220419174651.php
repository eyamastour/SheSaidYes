<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419174651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id_abonnement INT AUTO_INCREMENT NOT NULL, type_abonnenemt VARCHAR(45) NOT NULL, date_debut_abonnement VARCHAR(45) NOT NULL, date_fin_abonnement VARCHAR(45) NOT NULL, prix VARCHAR(45) DEFAULT NULL, PRIMARY KEY(id_abonnement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis CHANGE idUser idUser INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0FE6E88D7 FOREIGN KEY (idUser) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0FE6E88D7 ON avis (idUser)');
        $this->addSql('ALTER TABLE packs ADD promo INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation CHANGE idUser idUser INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404FE6E88D7 FOREIGN KEY (idUser) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_CE606404FE6E88D7 ON reclamation (idUser)');
        $this->addSql('ALTER TABLE reservationpack CHANGE iduser iduser INT DEFAULT NULL, CHANGE idpack idpack INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservationpack ADD CONSTRAINT FK_8FB76A065E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservationpack ADD CONSTRAINT FK_8FB76A064411AF83 FOREIGN KEY (idpack) REFERENCES packs (id)');
        $this->addSql('CREATE INDEX IDX_8FB76A065E5C27E9 ON reservationpack (iduser)');
        $this->addSql('CREATE INDEX IDX_8FB76A064411AF83 ON reservationpack (idpack)');
        $this->addSql('ALTER TABLE reservationservice CHANGE idservice idservice INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservationservice ADD CONSTRAINT FK_505F34CA5E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservationservice ADD CONSTRAINT FK_505F34CA3E99C8BC FOREIGN KEY (idservice) REFERENCES services (id)');
        $this->addSql('CREATE INDEX IDX_505F34CA5E5C27E9 ON reservationservice (iduser)');
        $this->addSql('CREATE INDEX IDX_505F34CA3E99C8BC ON reservationservice (idservice)');
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY fk_p');
        $this->addSql('ALTER TABLE services CHANGE idPack idPack INT DEFAULT NULL');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E169E42300BD FOREIGN KEY (idPack) REFERENCES packs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0FE6E88D7');
        $this->addSql('DROP INDEX IDX_8F91ABF0FE6E88D7 ON avis');
        $this->addSql('ALTER TABLE avis CHANGE idUser idUser INT NOT NULL');
        $this->addSql('ALTER TABLE packs DROP promo');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404FE6E88D7');
        $this->addSql('DROP INDEX IDX_CE606404FE6E88D7 ON reclamation');
        $this->addSql('ALTER TABLE reclamation CHANGE idUser idUser INT NOT NULL');
        $this->addSql('ALTER TABLE reservationpack DROP FOREIGN KEY FK_8FB76A065E5C27E9');
        $this->addSql('ALTER TABLE reservationpack DROP FOREIGN KEY FK_8FB76A064411AF83');
        $this->addSql('DROP INDEX IDX_8FB76A065E5C27E9 ON reservationpack');
        $this->addSql('DROP INDEX IDX_8FB76A064411AF83 ON reservationpack');
        $this->addSql('ALTER TABLE reservationpack CHANGE iduser iduser INT NOT NULL, CHANGE idpack idpack INT NOT NULL');
        $this->addSql('ALTER TABLE reservationservice DROP FOREIGN KEY FK_505F34CA5E5C27E9');
        $this->addSql('ALTER TABLE reservationservice DROP FOREIGN KEY FK_505F34CA3E99C8BC');
        $this->addSql('DROP INDEX IDX_505F34CA5E5C27E9 ON reservationservice');
        $this->addSql('DROP INDEX IDX_505F34CA3E99C8BC ON reservationservice');
        $this->addSql('ALTER TABLE reservationservice CHANGE idservice idservice INT NOT NULL');
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E169E42300BD');
        $this->addSql('ALTER TABLE services CHANGE idPack idPack INT NOT NULL');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT fk_p FOREIGN KEY (idPack) REFERENCES packs (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
