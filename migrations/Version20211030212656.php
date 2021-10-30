<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211030212656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add cascade deletion on project->step';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE step DROP CONSTRAINT FK_43B9FE3C166D1F9C');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE step DROP CONSTRAINT fk_43b9fe3c166d1f9c');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT fk_43b9fe3c166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
