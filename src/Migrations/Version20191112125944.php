<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191112125944 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE photo_user (photo_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_CA264BD7E9E4C8C (photo_id), INDEX IDX_CA264BDA76ED395 (user_id), PRIMARY KEY(photo_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photo_user ADD CONSTRAINT FK_CA264BD7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo_user ADD CONSTRAINT FK_CA264BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491EA29AA6');
        $this->addSql('DROP INDEX IDX_8D93D6491EA29AA6 ON user');
        $this->addSql('ALTER TABLE user DROP photoslike_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE photo_user');
        $this->addSql('ALTER TABLE user ADD photoslike_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491EA29AA6 FOREIGN KEY (photoslike_id) REFERENCES photo (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491EA29AA6 ON user (photoslike_id)');
    }
}
