<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221130092108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, shortname VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, start TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ranking (id INT AUTO_INCREMENT NOT NULL, endrun TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, grade_id INT DEFAULT NULL, start_id INT DEFAULT NULL, ranking_id INT DEFAULT NULL, barcode VARCHAR(50) NOT NULL, firstname VARCHAR(20) NOT NULL, lastname VARCHAR(20) NOT NULL, mas DOUBLE PRECISION NOT NULL, gender VARCHAR(10) NOT NULL, objective TIME NOT NULL, INDEX IDX_B723AF33FE19A1A8 (grade_id), INDEX IDX_B723AF33623DF99B (start_id), INDEX IDX_B723AF3320F64684 (ranking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33623DF99B FOREIGN KEY (start_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3320F64684 FOREIGN KEY (ranking_id) REFERENCES ranking (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33FE19A1A8');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33623DF99B');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3320F64684');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE race');
        $this->addSql('DROP TABLE ranking');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
