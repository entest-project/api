<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113213723 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create types';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        $this->addSql("CREATE TYPE step_part_type AS ENUM ('sentence', 'param')");
        $this->addSql("CREATE TYPE step_type AS ENUM ('given', 'when', 'then')");
        $this->addSql("CREATE TYPE step_adverb AS ENUM ('given', 'when', 'then', 'and', 'but')");
        $this->addSql("CREATE TYPE param_type AS ENUM ('inline', 'multiline', 'table')");
        $this->addSql("CREATE TYPE step_extra_param_type AS ENUM ('none', 'multiline', 'table')");
        $this->addSql("CREATE TYPE scenario_type AS ENUM ('background', 'outline', 'regular')");
        $this->addSql("CREATE TYPE project_visibility AS ENUM ('public', 'internal', 'private')");
        $this->addSql("CREATE TYPE <feature_status> AS ENUM ('draft', 'ready_to_dev', 'live')");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DROP TYPE step_part_type");
    }
}
