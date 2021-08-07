<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210807134946 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Changed multiline step param content type';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE multiline_step_param ALTER content TYPE TEXT');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE multiline_step_param ALTER content TYPE VARCHAR(255)');
    }
}
