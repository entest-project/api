<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240110133728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added API URL for issue tracker configuration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration ADD api_url VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration DROP api_url');
    }
}
