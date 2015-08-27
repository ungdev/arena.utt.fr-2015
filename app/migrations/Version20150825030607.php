<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150825030607 extends AbstractMigration
{
    protected $spotlightGames = array(
        'lol' => array(
            'name' => 'League of Legends',
            'maximum' => 16,
            'discr' => 'team'
        ),
        'csgo' => array(
            'name' => 'Counter-Strike : Global Offensive',
            'maximum' => 8,
            'discr' => 'team'
        ),
        'hearthstone' => array(
            'name' => 'Hearthstone',
            'maximum' => 32,
            'discr' => 'single'
        ),
        'usf4' => array(
            'name' => 'Ultimate Street-Figther 4',
            'maximum' => 32,
            'discr' => 'single'
        )
    );


    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        foreach ($this->spotlightGames as $slug => $game) {
            $this->addSql(
                "INSERT INTO spotlight_games (name,slug, discr, maximum)
                VALUES (:name, :slug, :discr, :maximum) ",
                array_merge($game, array('slug' => $slug))
            );
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        foreach (array_keys($this->spotlightGames) as $slug) {
            $this->addSql(
                "DELETE FROM spotlight_games WHERE slug = :slug ",
                 array('slug' => $slug)
            );
        }
    }

    public function getDescription() {
        return 'Insertion of the spotlight games';
    }
}
