<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130034831 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE locker_request (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, address VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, lock_count INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B14F52C0979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE locker_request ADD CONSTRAINT FK_B14F52C0979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE admin ADD login VARCHAR(255) NOT NULL, DROP email, DROP name, DROP phone, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE locker ADD name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE locker_request');
        $this->addSql('ALTER TABLE admin ADD name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD phone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE login email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE locker DROP name');
    }
}
