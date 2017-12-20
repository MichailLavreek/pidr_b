<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171217152840 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE language DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE language DROP id');
        $this->addSql('ALTER TABLE language ADD PRIMARY KEY (iso2)');
        $this->addSql('ALTER TABLE structure_language DROP INDEX UNIQ_85DED3FF2534008B, ADD INDEX IDX_85DED3FF2534008B (structure_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE language DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE language ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE language ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE structure_language DROP INDEX IDX_85DED3FF2534008B, ADD UNIQUE INDEX UNIQ_85DED3FF2534008B (structure_id)');
    }
}
