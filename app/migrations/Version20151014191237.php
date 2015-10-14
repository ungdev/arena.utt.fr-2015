<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151014191237 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE tickets DROP INDEX UNIQ_54469DF474D452, ADD INDEX IDX_54469DF474D452 (tshirt_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE tickets DROP INDEX IDX_54469DF474D452, ADD UNIQUE INDEX UNIQ_54469DF474D452 (tshirt_id)');
    }
}
