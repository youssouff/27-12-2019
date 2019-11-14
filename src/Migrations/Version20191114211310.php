<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191114211310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE evenement_user DROP FOREIGN KEY FK_2EC0B3C4A76ED395');
        $this->addSql('ALTER TABLE order_history DROP FOREIGN KEY FK_D1C0D900F675F31B');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418F675F31B');
        $this->addSql('ALTER TABLE photo_user DROP FOREIGN KEY FK_CA264BDA76ED395');
        $this->addSql('DROP TABLE evenement_user');
        $this->addSql('DROP TABLE photo_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B ON comment');
        $this->addSql('ALTER TABLE comment ADD author VARCHAR(255) NOT NULL, DROP author_id');
        $this->addSql('ALTER TABLE evenement ADD participant JSON NOT NULL');
        $this->addSql('DROP INDEX IDX_D1C0D900F675F31B ON order_history');
        $this->addSql('ALTER TABLE order_history ADD author VARCHAR(255) NOT NULL, DROP author_id');
        $this->addSql('DROP INDEX IDX_14B78418F675F31B ON photo');
        $this->addSql('ALTER TABLE photo ADD author VARCHAR(255) NOT NULL, ADD users JSON NOT NULL, DROP author_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE evenement_user (evenement_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2EC0B3C4FD02F13 (evenement_id), INDEX IDX_2EC0B3C4A76ED395 (user_id), PRIMARY KEY(evenement_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE photo_user (photo_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_CA264BD7E9E4C8C (photo_id), INDEX IDX_CA264BDA76ED395 (user_id), PRIMARY KEY(photo_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE evenement_user ADD CONSTRAINT FK_2EC0B3C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_user ADD CONSTRAINT FK_2EC0B3C4FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_user ADD CONSTRAINT FK_CA264BD7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_user ADD CONSTRAINT FK_CA264BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD author_id INT NOT NULL, DROP author');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('ALTER TABLE evenement DROP participant');
        $this->addSql('ALTER TABLE order_history ADD author_id INT NOT NULL, DROP author');
        $this->addSql('ALTER TABLE order_history ADD CONSTRAINT FK_D1C0D900F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D1C0D900F675F31B ON order_history (author_id)');
        $this->addSql('ALTER TABLE photo ADD author_id INT NOT NULL, DROP author, DROP users');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_14B78418F675F31B ON photo (author_id)');
    }
}
