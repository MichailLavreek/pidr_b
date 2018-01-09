<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180109214748 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1FF920BBA2');
        $this->addSql('DROP TABLE attribute_value');
        $this->addSql('DROP TABLE products_attributes');
        $this->addSql('DROP INDEX IDX_CCC4BE1FF920BBA2 ON product_attribute_value');
        $this->addSql('ALTER TABLE product_attribute_value ADD value VARCHAR(50) NOT NULL, DROP value_id');
        $this->addSql('ALTER TABLE attribute_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE content_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE language CHANGE iso2 iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE product_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE structure_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attribute_value (id INT AUTO_INCREMENT NOT NULL, attribute_id INT DEFAULT NULL, value VARCHAR(30) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_FE4FBB82B6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_attributes (product_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_E3C4666E4584665A (product_id), INDEX IDX_E3C4666EB6E62EFA (attribute_id), PRIMARY KEY(product_id, attribute_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribute_value ADD CONSTRAINT FK_FE4FBB82B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE products_attributes ADD CONSTRAINT FK_E3C4666E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_attributes ADD CONSTRAINT FK_E3C4666EB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attribute_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE content_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE language CHANGE iso2 iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE product_attribute_value ADD value_id INT NOT NULL, DROP value');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1FF920BBA2 FOREIGN KEY (value_id) REFERENCES attribute_value (id)');
        $this->addSql('CREATE INDEX IDX_CCC4BE1FF920BBA2 ON product_attribute_value (value_id)');
        $this->addSql('ALTER TABLE product_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE structure_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
    }
}
