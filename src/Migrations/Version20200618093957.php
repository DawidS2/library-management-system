<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618093957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rent (id INT AUTO_INCREMENT NOT NULL, specimen_id INT NOT NULL, reader_id INT NOT NULL, rent_at DATE NOT NULL, rent_to DATE NOT NULL, is_returned TINYINT(1) NOT NULL, INDEX IDX_2784DCCBF112A8 (specimen_id), INDEX IDX_2784DCC1717D737 (reader_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCBF112A8 FOREIGN KEY (specimen_id) REFERENCES specimen (id)');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCC1717D737 FOREIGN KEY (reader_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE rent');
    }
}
