<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180211105857 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_montage (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, language_id CHAR(2) NOT NULL, status VARCHAR(255) NOT NULL, product_price INT DEFAULT NULL, quadrature VARCHAR(100) DEFAULT NULL, client_name VARCHAR(200) NOT NULL, client_phone VARCHAR(200) NOT NULL, client_address VARCHAR(250) NOT NULL, created_at DATETIME NOT NULL, order_date DATE NOT NULL, order_queue INT NOT NULL, INDEX IDX_E4FBD1D84584665A (product_id), INDEX IDX_E4FBD1D882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_montage ADD CONSTRAINT FK_E4FBD1D84584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_montage ADD CONSTRAINT FK_E4FBD1D882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (iso2)');
        $this->addSql('DROP TABLE `order`');
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

        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, language_id CHAR(2) NOT NULL COLLATE utf8_unicode_ci, product_price INT DEFAULT NULL, quadrature VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, client_name VARCHAR(200) NOT NULL COLLATE utf8_unicode_ci, client_phone VARCHAR(200) NOT NULL COLLATE utf8_unicode_ci, client_address VARCHAR(250) NOT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, order_date DATE NOT NULL, order_queue INT NOT NULL, status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_F52993984584665A (product_id), INDEX IDX_F529939882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (iso2)');
        $this->addSql('DROP TABLE order_montage');
        $this->addSql('ALTER TABLE attribute_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE content_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE language CHANGE iso2 iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE product_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE structure_language CHANGE language_iso2 language_iso2 CHAR(2) NOT NULL COLLATE utf8_unicode_ci');
    }
}
