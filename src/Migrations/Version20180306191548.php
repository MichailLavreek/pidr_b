<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180306191548 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE meta (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meta_language (id INT AUTO_INCREMENT NOT NULL, meta_id INT DEFAULT NULL, language_iso2 CHAR(2) NOT NULL, title VARCHAR(500) DEFAULT NULL, description LONGTEXT DEFAULT NULL, keywords VARCHAR(250) DEFAULT NULL, INDEX IDX_9A9689FE39FCA6F9 (meta_id), INDEX IDX_9A9689FE29667D38 (language_iso2), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meta_language ADD CONSTRAINT FK_9A9689FE39FCA6F9 FOREIGN KEY (meta_id) REFERENCES meta (id)');
        $this->addSql('ALTER TABLE meta_language ADD CONSTRAINT FK_9A9689FE29667D38 FOREIGN KEY (language_iso2) REFERENCES language (iso2)');
        $this->addSql('ALTER TABLE attribute_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE content ADD meta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A939FCA6F9 FOREIGN KEY (meta_id) REFERENCES meta (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEC530A939FCA6F9 ON content (meta_id)');
        $this->addSql('ALTER TABLE content_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE language CHANGE iso2 iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE order_montage CHANGE language_id language_id CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE product ADD meta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD39FCA6F9 FOREIGN KEY (meta_id) REFERENCES meta (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD39FCA6F9 ON product (meta_id)');
        $this->addSql('ALTER TABLE product_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE structure_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A939FCA6F9');
        $this->addSql('ALTER TABLE meta_language DROP FOREIGN KEY FK_9A9689FE39FCA6F9');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD39FCA6F9');
        $this->addSql('DROP TABLE meta');
        $this->addSql('DROP TABLE meta_language');
        $this->addSql('ALTER TABLE attribute_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_FEC530A939FCA6F9 ON content');
        $this->addSql('ALTER TABLE content DROP meta_id');
        $this->addSql('ALTER TABLE content_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE language CHANGE iso2 iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE order_montage CHANGE language_id language_id CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_D34A04AD39FCA6F9 ON product');
        $this->addSql('ALTER TABLE product DROP meta_id');
        $this->addSql('ALTER TABLE product_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE structure_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
    }
}
