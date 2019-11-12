<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191112124345 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B7841867B3B43D');
        $this->addSql('DROP INDEX IDX_14B7841867B3B43D ON photo');
        $this->addSql('ALTER TABLE photo DROP users_id');
        $this->addSql('ALTER TABLE user ADD photoslike_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491EA29AA6 FOREIGN KEY (photoslike_id) REFERENCES photo (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491EA29AA6 ON user (photoslike_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B7841867B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_14B7841867B3B43D ON photo (users_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491EA29AA6');
        $this->addSql('DROP INDEX IDX_8D93D6491EA29AA6 ON user');
        $this->addSql('ALTER TABLE user DROP photoslike_id');
    }
}
