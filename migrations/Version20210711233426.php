<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210711233426 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add unique index on paths';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B548B0F989D9B62727ACA70 ON path (slug, parent_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX UNIQ_B548B0F989D9B62727ACA70');
    }
}
