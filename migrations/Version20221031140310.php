<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221031140310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add an option to know if we should consider first column or first row as a header';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE table_step_param ADD header_column BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE table_step_param ADD header_row BOOLEAN DEFAULT \'false\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE table_step_param DROP header_column');
        $this->addSql('ALTER TABLE table_step_param DROP header_row');
    }
}
