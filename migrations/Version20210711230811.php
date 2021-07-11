<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210711230811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add real unique constraint on projects names';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE32C8A3DE989D9B62_BIS ON project (COALESCE(organization_id, \'00000000-0000-0000-0000-000000000000\'), slug)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE32C8A3DE989D9B62_BIS');
    }
}
