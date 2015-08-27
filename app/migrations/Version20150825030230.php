<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150825030230 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE spotlight_games (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(128) NOT NULL, maximum INT NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B49C5B875E237E06 (name), UNIQUE INDEX UNIQ_B49C5B87989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_spotlight_games (id INT NOT NULL, teammate_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teams (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, game_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_96C222585E237E06 (name), UNIQUE INDEX UNIQ_96C22258989D9B62 (slug), UNIQUE INDEX UNIQ_96C22258B03A8386 (created_by_id), INDEX IDX_96C22258E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE memberships (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, team_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_865A477699E6F5DF (player_id), INDEX IDX_865A4776296CD8AE (team_id), UNIQUE INDEX UNIQ_membership (player_id, team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team_spotlight_games ADD CONSTRAINT FK_97C241F9BF396750 FOREIGN KEY (id) REFERENCES spotlight_games (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teams ADD CONSTRAINT FK_96C22258B03A8386 FOREIGN KEY (created_by_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE teams ADD CONSTRAINT FK_96C22258E48FD905 FOREIGN KEY (game_id) REFERENCES team_spotlight_games (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE memberships ADD CONSTRAINT FK_865A477699E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE memberships ADD CONSTRAINT FK_865A4776296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE players ADD spotlight_game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6615EAB8 FOREIGN KEY (spotlight_game_id) REFERENCES spotlight_games (id)');
        $this->addSql('CREATE INDEX IDX_264E43A6615EAB8 ON players (spotlight_game_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6615EAB8');
        $this->addSql('ALTER TABLE team_spotlight_games DROP FOREIGN KEY FK_97C241F9BF396750');
        $this->addSql('ALTER TABLE teams DROP FOREIGN KEY FK_96C22258E48FD905');
        $this->addSql('ALTER TABLE memberships DROP FOREIGN KEY FK_865A4776296CD8AE');
        $this->addSql('DROP TABLE spotlight_games');
        $this->addSql('DROP TABLE team_spotlight_games');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE memberships');
        $this->addSql('DROP INDEX IDX_264E43A6615EAB8 ON players');
        $this->addSql('ALTER TABLE players DROP spotlight_game_id');
    }
}
