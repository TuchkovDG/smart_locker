<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190125223513 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `lock` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, locker_id INT NOT NULL, status SMALLINT NOT NULL, reserved_at DATETIME DEFAULT NULL, INDEX IDX_878F9B0EA76ED395 (user_id), INDEX IDX_878F9B0E841CF1E0 (locker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uid VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locker (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, address VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1E067DC0979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `lock` ADD CONSTRAINT FK_878F9B0EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `lock` ADD CONSTRAINT FK_878F9B0E841CF1E0 FOREIGN KEY (locker_id) REFERENCES locker (id)');
        $this->addSql('ALTER TABLE locker ADD CONSTRAINT FK_1E067DC0979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE locker DROP FOREIGN KEY FK_1E067DC0979B1AD6');
        $this->addSql('ALTER TABLE `lock` DROP FOREIGN KEY FK_878F9B0EA76ED395');
        $this->addSql('ALTER TABLE `lock` DROP FOREIGN KEY FK_878F9B0E841CF1E0');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE `lock`');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE locker');
    }
}
