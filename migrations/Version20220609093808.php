<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609093808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA9D86650F');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAB46B9EE8');
        $this->addSql('DROP INDEX IDX_659DF2AA9D86650F ON chat');
        $this->addSql('DROP INDEX IDX_659DF2AAB46B9EE8 ON chat');
        $this->addSql('ALTER TABLE chat ADD trick_id INT NOT NULL, ADD user_id INT NOT NULL, DROP trick_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAB281BE2E ON chat (trick_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAA76ED395 ON chat (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAB281BE2E');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAA76ED395');
        $this->addSql('DROP INDEX IDX_659DF2AAB281BE2E ON chat');
        $this->addSql('DROP INDEX IDX_659DF2AAA76ED395 ON chat');
        $this->addSql('ALTER TABLE chat ADD trick_id_id INT NOT NULL, ADD user_id_id INT NOT NULL, DROP trick_id, DROP user_id');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAB46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_659DF2AA9D86650F ON chat (user_id_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAB46B9EE8 ON chat (trick_id_id)');
    }
}
