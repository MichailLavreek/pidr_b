<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180211093525 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attribute_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE content_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE language CHANGE iso2 iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE product_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE structure_language ADD description LONGTEXT DEFAULT NULL, CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL, CHANGE name name VARCHAR(200) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attribute_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE content_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE language CHANGE iso2 iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE product_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE structure_language DROP description, CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci, CHANGE name name TINYTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
