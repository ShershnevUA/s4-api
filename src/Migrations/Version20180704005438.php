<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180704005438 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE users_id_seq INCREMENT BY 1');
        $this->addSql('CREATE SEQUENCE chanel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE chanel (id INT NOT NULL, owner_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, private BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BBD24067E3C61F9 ON chanel (owner_id)');
        $this->addSql('CREATE TABLE chanel_user (chanel_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(chanel_id, user_id))');
        $this->addSql('CREATE INDEX IDX_5ED4DF1326F4971E ON chanel_user (chanel_id)');
        $this->addSql('CREATE INDEX IDX_5ED4DF13A76ED395 ON chanel_user (user_id)');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, author_id INT NOT NULL, chanel_id INT DEFAULT NULL, body VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307FF675F31B ON message (author_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F26F4971E ON message (chanel_id)');
        $this->addSql('ALTER TABLE chanel ADD CONSTRAINT FK_BBD24067E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chanel_user ADD CONSTRAINT FK_5ED4DF1326F4971E FOREIGN KEY (chanel_id) REFERENCES chanel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chanel_user ADD CONSTRAINT FK_5ED4DF13A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F26F4971E FOREIGN KEY (chanel_id) REFERENCES chanel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chanel_user DROP CONSTRAINT FK_5ED4DF1326F4971E');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F26F4971E');
        $this->addSql('ALTER SEQUENCE users_id_seq INCREMENT BY 1');
        $this->addSql('DROP SEQUENCE chanel_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP TABLE chanel');
        $this->addSql('DROP TABLE chanel_user');
        $this->addSql('DROP TABLE message');
    }
}
