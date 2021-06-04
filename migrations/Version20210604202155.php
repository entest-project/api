<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210604202155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add tags';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE tag (id UUID NOT NULL DEFAULT uuid_generate_v4(), project_id UUID NOT NULL, name VARCHAR(50) NOT NULL, color VARCHAR(7) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_389B783166D1F9C ON tag (project_id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE tag');
    }
}
