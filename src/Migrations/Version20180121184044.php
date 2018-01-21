<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180121184044 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attribute (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_FA7AEFFB77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_language (id INT AUTO_INCREMENT NOT NULL, attribute_id INT DEFAULT NULL, language_iso2 CHAR(2) NOT NULL, name TINYTEXT NOT NULL, INDEX IDX_4859EB45B6E62EFA (attribute_id), INDEX IDX_4859EB4529667D38 (language_iso2), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, structure_id INT NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_FEC530A92534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_language (id INT AUTO_INCREMENT NOT NULL, content_id INT DEFAULT NULL, language_iso2 CHAR(2) NOT NULL, name TINYTEXT DEFAULT NULL, body LONGTEXT DEFAULT NULL, INDEX IDX_866C9B5A84A0A3ED (content_id), INDEX IDX_866C9B5A29667D38 (language_iso2), UNIQUE INDEX unique_content_language (language_iso2, content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (iso2 CHAR(2) NOT NULL, name TINYTEXT NOT NULL, PRIMARY KEY(iso2)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, structure_id INT NOT NULL, alias VARCHAR(200) NOT NULL, is_active TINYINT(1) NOT NULL, code VARCHAR(50) NOT NULL, price INT NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D34A04ADE16C6B94 (alias), INDEX IDX_D34A04AD2534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attribute_value (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, attribute_id INT NOT NULL, value VARCHAR(50) NOT NULL, INDEX IDX_CCC4BE1F4584665A (product_id), INDEX IDX_CCC4BE1FB6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_language (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, language_iso2 CHAR(2) NOT NULL, name TINYTEXT DEFAULT NULL, INDEX IDX_1F6B1B224584665A (product_id), INDEX IDX_1F6B1B2229667D38 (language_iso2), UNIQUE INDEX unique_product_language (language_iso2, product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL, system_name VARCHAR(25) NOT NULL, UNIQUE INDEX UNIQ_984DB5EB5E237E06 (name), UNIQUE INDEX UNIQ_984DB5EB4FEFCDF0 (system_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sys_aoe_staff (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, last_name VARCHAR(20) NOT NULL, middle_name VARCHAR(20) DEFAULT NULL, first_name VARCHAR(20) NOT NULL, gender VARCHAR(255) DEFAULT NULL, priv_email VARCHAR(50) DEFAULT NULL, priv_phone VARCHAR(20) DEFAULT NULL, address VARCHAR(50) DEFAULT NULL, email_work VARCHAR(50) DEFAULT NULL, phone_work_private VARCHAR(20) DEFAULT NULL, created_at DATETIME NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, parent INT DEFAULT NULL, type_id INT DEFAULT NULL, alias VARCHAR(200) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_6F0137EAE16C6B94 (alias), INDEX IDX_6F0137EA3D8E604F (parent), INDEX IDX_6F0137EAC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure_language (id INT AUTO_INCREMENT NOT NULL, structure_id INT NOT NULL, language_iso2 CHAR(2) NOT NULL, name TINYTEXT DEFAULT NULL, INDEX IDX_85DED3FF2534008B (structure_id), INDEX IDX_85DED3FF29667D38 (language_iso2), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cms_users (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(60) DEFAULT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_3AF03EC5F85E0677 (username), UNIQUE INDEX UNIQ_3AF03EC5E7927C74 (email), UNIQUE INDEX UNIQ_3AF03EC5D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variable (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, value VARCHAR(200) NOT NULL, UNIQUE INDEX UNIQ_CC4D878D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribute_language ADD CONSTRAINT FK_4859EB45B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE attribute_language ADD CONSTRAINT FK_4859EB4529667D38 FOREIGN KEY (language_iso2) REFERENCES language (iso2)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A92534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE content_language ADD CONSTRAINT FK_866C9B5A84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id)');
        $this->addSql('ALTER TABLE content_language ADD CONSTRAINT FK_866C9B5A29667D38 FOREIGN KEY (language_iso2) REFERENCES language (iso2)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1FB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE product_language ADD CONSTRAINT FK_1F6B1B224584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_language ADD CONSTRAINT FK_1F6B1B2229667D38 FOREIGN KEY (language_iso2) REFERENCES language (iso2)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EA3D8E604F FOREIGN KEY (parent) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAC54C8C93 FOREIGN KEY (type_id) REFERENCES structure_type (id)');
        $this->addSql('ALTER TABLE structure_language ADD CONSTRAINT FK_85DED3FF2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE structure_language ADD CONSTRAINT FK_85DED3FF29667D38 FOREIGN KEY (language_iso2) REFERENCES language (iso2)');
        $this->addSql('ALTER TABLE cms_users ADD CONSTRAINT FK_3AF03EC5D60322AC FOREIGN KEY (role_id) REFERENCES cms_roles (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attribute_language DROP FOREIGN KEY FK_4859EB45B6E62EFA');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1FB6E62EFA');
        $this->addSql('ALTER TABLE content_language DROP FOREIGN KEY FK_866C9B5A84A0A3ED');
        $this->addSql('ALTER TABLE attribute_language DROP FOREIGN KEY FK_4859EB4529667D38');
        $this->addSql('ALTER TABLE content_language DROP FOREIGN KEY FK_866C9B5A29667D38');
        $this->addSql('ALTER TABLE product_language DROP FOREIGN KEY FK_1F6B1B2229667D38');
        $this->addSql('ALTER TABLE structure_language DROP FOREIGN KEY FK_85DED3FF29667D38');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F4584665A');
        $this->addSql('ALTER TABLE product_language DROP FOREIGN KEY FK_1F6B1B224584665A');
        $this->addSql('ALTER TABLE cms_users DROP FOREIGN KEY FK_3AF03EC5D60322AC');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A92534008B');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2534008B');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EA3D8E604F');
        $this->addSql('ALTER TABLE structure_language DROP FOREIGN KEY FK_85DED3FF2534008B');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAC54C8C93');
        $this->addSql('DROP TABLE attribute');
        $this->addSql('DROP TABLE attribute_language');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE content_language');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_attribute_value');
        $this->addSql('DROP TABLE product_language');
        $this->addSql('DROP TABLE cms_roles');
        $this->addSql('DROP TABLE sys_aoe_staff');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE structure_language');
        $this->addSql('DROP TABLE structure_type');
        $this->addSql('DROP TABLE cms_users');
        $this->addSql('DROP TABLE variable');
    }
}
