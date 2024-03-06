<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306133706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossiermedical ADD patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dossiermedical ADD CONSTRAINT FK_6699B4F06B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6699B4F06B899279 ON dossiermedical (patient_id)');
        $this->addSql('ALTER TABLE emploi CHANGE description description VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE rendezvous CHANGE description description VARCHAR(25555) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossiermedical DROP FOREIGN KEY FK_6699B4F06B899279');
        $this->addSql('DROP INDEX UNIQ_6699B4F06B899279 ON dossiermedical');
        $this->addSql('ALTER TABLE dossiermedical DROP patient_id');
        $this->addSql('ALTER TABLE emploi CHANGE description description MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendezvous CHANGE description description MEDIUMTEXT DEFAULT NULL');
    }
}
