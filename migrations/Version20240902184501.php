<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240902184501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add "fake data" strategy';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TYPE step_part_strategy ADD VALUE \'fake_data\'');
    }

    public function down(Schema $schema): void
    {
    }
}
