<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150726153943 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE players (id INT AUTO_INCREMENT NOT NULL, ticket_id INT DEFAULT NULL, nickname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(15) NOT NULL, enabled TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_264E43A6A188FE64 (nickname), UNIQUE INDEX UNIQ_264E43A6E7927C74 (email), UNIQUE INDEX UNIQ_264E43A65F37A13B (token), UNIQUE INDEX UNIQ_264E43A6700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tshirts (id INT AUTO_INCREMENT NOT NULL, size VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6CF6F579F7C0246A (size), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, tshirt_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, price INT NOT NULL, reduced TINYINT(1) NOT NULL, method VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_54469DF477153098 (code), UNIQUE INDEX UNIQ_54469DF474D452 (tshirt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF474D452 FOREIGN KEY (tshirt_id) REFERENCES tshirts (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF474D452');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6700047D2');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE tshirt');
        $this->addSql('DROP TABLE tickets');
    }
}
