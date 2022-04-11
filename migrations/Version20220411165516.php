<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411165516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create strategy for step parts';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TYPE step_part_strategy AS ENUM ('free', 'choices')");
        $this->addSql("ALTER TABLE step_part ADD strategy step_part_strategy DEFAULT NULL");
        $this->addSql('ALTER TABLE step_part ADD choices JSON DEFAULT NULL');
        $this->addSql("UPDATE step_part SET strategy = 'free' WHERE type = 'param'");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE step_part DROP strategy');
        $this->addSql('ALTER TABLE step_part DROP choices');
        $this->addSql('DROP TYPE step_part_strategy');
    }
}
