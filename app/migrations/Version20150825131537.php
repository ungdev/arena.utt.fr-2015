<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150825131537 extends AbstractMigration
{

    protected $teamSpotlightGame = array(
        'lol' => 3,
        'csgo' => 5
    );

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        foreach ($this->teamSpotlightGame as $slug => $number) {
            $this->addSql(
                "INSERT INTO team_spotlight_games (id,teammate_number) VALUES (
                (SELECT id FROM spotlight_games WHERE slug = :slug), :number) ",
                array('slug' => $slug, 'number' => $number)
            );
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        foreach (array_keys($this->teamSpotlightGame) as $slug) {
            $this->addSql(
                "DELETE FROM team_spotlight_games WHERE id = (SELECT id FROM spotlight_games WHERE slug = :slug) ",
                array('slug' => $slug)
            );
        }
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getDescription() {
        return 'Insertion of the team spotlight games';
    }
}
