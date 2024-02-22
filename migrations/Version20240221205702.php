<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221205702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossiermedical CHANGE resultatexamen resultatexamen VARCHAR(25555) DEFAULT NULL, CHANGE antecedantpersonnelles antecedantpersonnelles VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE emploi CHANGE description description VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance ADD dossier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326C611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossiermedical (id)');
        $this->addSql('CREATE INDEX IDX_924B326C611C0C56 ON ordonnance (dossier_id)');
        $this->addSql('ALTER TABLE rendezvous CHANGE description description VARCHAR(25555) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossiermedical CHANGE resultatexamen resultatexamen MEDIUMTEXT DEFAULT NULL, CHANGE antecedantpersonnelles antecedantpersonnelles MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE emploi CHANGE description description MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326C611C0C56');
        $this->addSql('DROP INDEX IDX_924B326C611C0C56 ON ordonnance');
        $this->addSql('ALTER TABLE ordonnance DROP dossier_id');
        $this->addSql('ALTER TABLE rendezvous CHANGE description description MEDIUMTEXT DEFAULT NULL');
    }
}
