<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418031904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (idAvis INT AUTO_INCREMENT NOT NULL, rating INT NOT NULL, descriptionAvis VARCHAR(255) NOT NULL, idUser INT DEFAULT NULL, INDEX IDX_8F91ABF0FE6E88D7 (idUser), PRIMARY KEY(idAvis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0FE6E88D7 FOREIGN KEY (idUser) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX fk_serv ON espaceprestatairee');
        $this->addSql('ALTER TABLE espaceprestatairee DROP idplan, DROP idservice, DROP iduser');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY planning_ibfk_1');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF63E99C8BC FOREIGN KEY (idservice) REFERENCES services (id)');
        $this->addSql('DROP INDEX fk_ser ON planning');
        $this->addSql('CREATE INDEX IDX_D499BFF63E99C8BC ON planning (idservice)');
        $this->addSql('DROP INDEX idprest ON planning');
        $this->addSql('CREATE INDEX IDX_D499BFF635A58507 ON planning (idprest)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT planning_ibfk_1 FOREIGN KEY (idprest) REFERENCES espaceprestatairee (idprest)');
        $this->addSql('ALTER TABLE reclamation ADD imageReclamtion VARCHAR(255) NOT NULL, ADD etatreclamtion VARCHAR(255) NOT NULL, ADD idUser INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404FE6E88D7 FOREIGN KEY (idUser) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_CE606404FE6E88D7 ON reclamation (idUser)');
        $this->addSql('ALTER TABLE reservationpack ADD etat TINYINT(1) NOT NULL, CHANGE date date VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservationpack ADD CONSTRAINT FK_8FB76A065E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservationpack ADD CONSTRAINT FK_8FB76A064411AF83 FOREIGN KEY (idpack) REFERENCES packs (id)');
        $this->addSql('CREATE INDEX IDX_8FB76A065E5C27E9 ON reservationpack (iduser)');
        $this->addSql('CREATE INDEX IDX_8FB76A064411AF83 ON reservationpack (idpack)');
        $this->addSql('ALTER TABLE reservationservice ADD etat TINYINT(1) NOT NULL, CHANGE date date VARCHAR(10) DEFAULT NULL, CHANGE prixresserv prixresserv DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE reservationservice ADD CONSTRAINT FK_505F34CA5E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservationservice ADD CONSTRAINT FK_505F34CA3E99C8BC FOREIGN KEY (idservice) REFERENCES services (id)');
        $this->addSql('CREATE INDEX IDX_505F34CA5E5C27E9 ON reservationservice (iduser)');
        $this->addSql('CREATE INDEX IDX_505F34CA3E99C8BC ON reservationservice (idservice)');
        $this->addSql('ALTER TABLE services CHANGE idesp idesp INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE avis');
        $this->addSql('ALTER TABLE espaceprestatairee ADD idplan INT DEFAULT NULL, ADD idservice INT NOT NULL, ADD iduser INT DEFAULT NULL');
        $this->addSql('CREATE INDEX fk_serv ON espaceprestatairee (idservice)');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF63E99C8BC');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF63E99C8BC');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF635A58507');
        $this->addSql('DROP INDEX idx_d499bff63e99c8bc ON planning');
        $this->addSql('CREATE INDEX fk_ser ON planning (idservice)');
        $this->addSql('DROP INDEX idx_d499bff635a58507 ON planning');
        $this->addSql('CREATE INDEX idprest ON planning (idprest)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF63E99C8BC FOREIGN KEY (idservice) REFERENCES services (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF635A58507 FOREIGN KEY (idprest) REFERENCES espaceprestatairee (idprest)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404FE6E88D7');
        $this->addSql('DROP INDEX IDX_CE606404FE6E88D7 ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP imageReclamtion, DROP etatreclamtion, DROP idUser');
        $this->addSql('ALTER TABLE reservationpack DROP FOREIGN KEY FK_8FB76A065E5C27E9');
        $this->addSql('ALTER TABLE reservationpack DROP FOREIGN KEY FK_8FB76A064411AF83');
        $this->addSql('DROP INDEX IDX_8FB76A065E5C27E9 ON reservationpack');
        $this->addSql('DROP INDEX IDX_8FB76A064411AF83 ON reservationpack');
        $this->addSql('ALTER TABLE reservationpack DROP etat, CHANGE date date VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE reservationservice DROP FOREIGN KEY FK_505F34CA5E5C27E9');
        $this->addSql('ALTER TABLE reservationservice DROP FOREIGN KEY FK_505F34CA3E99C8BC');
        $this->addSql('DROP INDEX IDX_505F34CA5E5C27E9 ON reservationservice');
        $this->addSql('DROP INDEX IDX_505F34CA3E99C8BC ON reservationservice');
        $this->addSql('ALTER TABLE reservationservice DROP etat, CHANGE date date VARCHAR(10) NOT NULL, CHANGE prixresserv prixresserv DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE services CHANGE idesp idesp INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE utilisateur CHANGE id id INT DEFAULT NULL');
    }
}
