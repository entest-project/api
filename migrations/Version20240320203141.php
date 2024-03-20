<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240320203141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add extra param template';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE step ADD extra_param_template JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE step DROP extra_param_template');
    }
}
