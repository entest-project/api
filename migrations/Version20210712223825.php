<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210712223825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add reset password columns';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE app_user ADD last_reset_password_request TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE app_user ADD reset_password_code VARCHAR(50) DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_88BDF3E9B6FEB38C ON app_user (reset_password_code)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE app_user DROP last_reset_password_request');
        $this->addSql('ALTER TABLE app_user DROP reset_password_code');
    }
}
