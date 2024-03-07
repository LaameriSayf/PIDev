<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307130540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emploi CHANGE description description VARCHAR(25555) DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance ADD idpatient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance ADD CONSTRAINT FK_924B326CA6208F43 FOREIGN KEY (idpatient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_924B326CA6208F43 ON ordonnance (idpatient_id)');
        $this->addSql('ALTER TABLE rendezvous CHANGE description description VARCHAR(25555) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emploi CHANGE description description MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance DROP FOREIGN KEY FK_924B326CA6208F43');
        $this->addSql('DROP INDEX IDX_924B326CA6208F43 ON ordonnance');
        $this->addSql('ALTER TABLE ordonnance DROP idpatient_id');
        $this->addSql('ALTER TABLE rendezvous CHANGE description description MEDIUMTEXT DEFAULT NULL');
    }
}
