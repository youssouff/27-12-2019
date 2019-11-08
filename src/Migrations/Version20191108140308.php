<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191108140308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_goodies DROP FOREIGN KEY FK_E50376D28D9F6D38');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, quantity JSON NOT NULL, INDEX IDX_E52FFDEEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_goodies (orders_id INT NOT NULL, goodies_id INT NOT NULL, INDEX IDX_E20CADE2CFFE9AD6 (orders_id), INDEX IDX_E20CADE2BBFA5614 (goodies_id), PRIMARY KEY(orders_id, goodies_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE orders_goodies ADD CONSTRAINT FK_E20CADE2CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_goodies ADD CONSTRAINT FK_E20CADE2BBFA5614 FOREIGN KEY (goodies_id) REFERENCES goodies (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_goodies');
        $this->addSql('ALTER TABLE recursion CHANGE time_interval time_interval INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders_goodies DROP FOREIGN KEY FK_E20CADE2CFFE9AD6');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, quantity JSON NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_goodies (order_id INT NOT NULL, goodies_id INT NOT NULL, INDEX IDX_E50376D28D9F6D38 (order_id), INDEX IDX_E50376D2BBFA5614 (goodies_id), PRIMARY KEY(order_id, goodies_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_goodies ADD CONSTRAINT FK_E50376D28D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_goodies ADD CONSTRAINT FK_E50376D2BBFA5614 FOREIGN KEY (goodies_id) REFERENCES goodies (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_goodies');
        $this->addSql('ALTER TABLE recursion CHANGE time_interval time_interval DATETIME DEFAULT NULL');
    }
}
