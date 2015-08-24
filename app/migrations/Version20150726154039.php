<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150726154039 extends AbstractMigration
{

    protected $sizes = ['XL', 'L', 'M', 'S', 'XS'];
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        foreach($this->sizes as $size) {
            $this->addSql("INSERT INTO tshirts (size) VALUES (?)", array($size));
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        foreach($this->sizes as $size) {
            $this->addSql("DELETE FROm tshirts WHERE size = ?", array($size));
        }
    }
}
