<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830210550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rebuild foreign keys properly';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE inline_step_param DROP CONSTRAINT FK_13CFC238BF396750');
        $this->addSql('ALTER TABLE inline_step_param ADD CONSTRAINT FK_13CFC238BF396750 FOREIGN KEY (id) REFERENCES step_param (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE multiline_step_param DROP CONSTRAINT FK_9C00174BF396750');
        $this->addSql('ALTER TABLE multiline_step_param ADD CONSTRAINT FK_9C00174BF396750 FOREIGN KEY (id) REFERENCES step_param (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT FK_B49AE8D432C8A3DE');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D432C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE32C8A3DE');
        $this->addSql('DROP INDEX uniq_2fb3d0ee32c8a3de989d9b62_bis');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scenario_tag DROP CONSTRAINT FK_7ED15E1CE04E49DF');
        $this->addSql('ALTER TABLE scenario_tag ADD CONSTRAINT FK_7ED15E1CE04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scenario_tag ADD PRIMARY KEY (scenario_id, tag_id)');
        $this->addSql('ALTER TABLE step_tag DROP CONSTRAINT FK_9197B26173B21E9C');
        $this->addSql('ALTER TABLE step_tag ADD CONSTRAINT FK_9197B26173B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE step_tag ADD PRIMARY KEY (step_id, tag_id)');
        $this->addSql('ALTER TABLE table_step_param DROP CONSTRAINT FK_E536D31BBF396750');
        $this->addSql('ALTER TABLE table_step_param ADD CONSTRAINT FK_E536D31BBF396750 FOREIGN KEY (id) REFERENCES step_param (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP CONSTRAINT fk_2fb3d0ee32c8a3de');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT fk_2fb3d0ee32c8a3de FOREIGN KEY (organization_id) REFERENCES organization (id) ON UPDATE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2fb3d0ee32c8a3de989d9b62_bis ON project (slug)');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT fk_b49ae8d432c8a3de');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT fk_b49ae8d432c8a3de FOREIGN KEY (organization_id) REFERENCES organization (id) ON UPDATE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE multiline_step_param DROP CONSTRAINT fk_9c00174bf396750');
        $this->addSql('ALTER TABLE multiline_step_param ADD CONSTRAINT fk_9c00174bf396750 FOREIGN KEY (id) REFERENCES step_param (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scenario_tag DROP CONSTRAINT fk_7ed15e1ce04e49df');
        $this->addSql('ALTER TABLE scenario_tag DROP CONSTRAINT scenario_tag_pkey');
        $this->addSql('ALTER TABLE scenario_tag ADD CONSTRAINT fk_7ed15e1ce04e49df FOREIGN KEY (scenario_id) REFERENCES scenario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE step_tag DROP CONSTRAINT fk_9197b26173b21e9c');
        $this->addSql('ALTER TABLE step_tag DROP CONSTRAINT step_tag_pkey');
        $this->addSql('ALTER TABLE step_tag ADD CONSTRAINT fk_9197b26173b21e9c FOREIGN KEY (step_id) REFERENCES step (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE table_step_param DROP CONSTRAINT fk_e536d31bbf396750');
        $this->addSql('ALTER TABLE table_step_param ADD CONSTRAINT fk_e536d31bbf396750 FOREIGN KEY (id) REFERENCES step_param (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inline_step_param DROP CONSTRAINT fk_13cfc238bf396750');
        $this->addSql('ALTER TABLE inline_step_param ADD CONSTRAINT fk_13cfc238bf396750 FOREIGN KEY (id) REFERENCES step_param (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
