<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191117183117 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE beer CHANGE type type VARCHAR(20) NOT NULL, CHANGE brewer brewer_id INT NOT NULL');
        $this->addSql('ALTER TABLE beer ADD CONSTRAINT FK_58F666ADBCA4F952 FOREIGN KEY (brewer_id) REFERENCES brewer (id)');
        $this->addSql('CREATE INDEX IDX_58F666ADBCA4F952 ON beer (brewer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE beer DROP FOREIGN KEY FK_58F666ADBCA4F952');
        $this->addSql('DROP INDEX IDX_58F666ADBCA4F952 ON beer');
        $this->addSql('ALTER TABLE beer CHANGE type type VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE brewer_id brewer INT NOT NULL');
    }
}
