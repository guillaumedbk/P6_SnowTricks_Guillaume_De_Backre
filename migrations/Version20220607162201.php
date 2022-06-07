<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607162201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, trick_id_id INT NOT NULL, user_id_id INT NOT NULL, content LONGTEXT NOT NULL, publish_at DATETIME NOT NULL, INDEX IDX_659DF2AAB46B9EE8 (trick_id_id), INDEX IDX_659DF2AA9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAB46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAB46B9EE8');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA9D86650F');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE trick');
        $this->addSql('DROP TABLE user');
    }
}
