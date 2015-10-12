<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151012004745 extends AbstractMigration
{protected $sizes = ['XL', 'L', 'M', 'S', 'XS'];
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('DROP INDEX UNIQ_6CF6F579F7C0246A ON tshirts');
        $this->addSql("ALTER TABLE tshirts ADD gender VARCHAR(255) NOT NULL DEFAULT 'M'");
        foreach ($this->sizes as $size) {
            $this->addSql(
                "INSERT INTO tshirts (size, gender)
                    VALUES (:size, :gender)",
                array('size' => $size, 'gender' => 'F')
            );
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE tshirts DROP gender');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6CF6F579F7C0246A ON tshirts (size)');
    }
}
