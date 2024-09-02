<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240901090025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added fake data type to step parts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE step_part ADD fake_data_type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE step_part DROP fake_data_type');
    }
}
