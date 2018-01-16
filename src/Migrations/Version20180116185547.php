<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180116185547 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE structure_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE attribute_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE content_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_D4DB71B51B6F9774 ON language');
        $this->addSql('ALTER TABLE language CHANGE iso2 iso2 CHAR(2) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attribute_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE content_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE language CHANGE iso2 iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D4DB71B51B6F9774 ON language (iso2)');
        $this->addSql('ALTER TABLE product_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE structure_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
    }
}
