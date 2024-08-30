<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240830111352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change id type to UUID for several entities';
    }

    private function convertTableId(
        string $table,
        array $foreignKeys,
        bool $createId = true
    ) {
        if ($createId) {
            $this->addSql("ALTER TABLE $table ADD COLUMN new_id UUID");
            $this->addSql("UPDATE $table SET new_id = uuid_generate_v4()");
        }

        foreach ($foreignKeys as $depTable => $foreignKeyDefinition) {
            $foreignKey = is_array($foreignKeyDefinition) ? $foreignKeyDefinition['key'] : $foreignKeyDefinition;
            $columnTempName = sprintf('temp_%s_uuid', $table);
            $columnOldName = is_array($foreignKeyDefinition) ? $foreignKeyDefinition['column'] : sprintf('%s_%s', $table, 'id');
            $columnFullOldName = sprintf('%s.%s', $depTable, $columnOldName);

            $this->addSql(sprintf("ALTER TABLE %s ADD COLUMN %s UUID", $depTable, $columnTempName));
            $this->addSql(sprintf("UPDATE %s SET %s = (SELECT new_id FROM %s WHERE id = %s)", $depTable, $columnTempName, $table, $columnFullOldName));
            $this->addSql(sprintf("ALTER TABLE %s DROP CONSTRAINT %s", $depTable, $foreignKey));
            $this->addSql(sprintf("ALTER TABLE %s DROP COLUMN %s", $depTable, $columnOldName));
            $this->addSql(sprintf("ALTER TABLE %s RENAME COLUMN %s TO %s", $depTable, $columnTempName, $columnOldName));
        }

        $this->addSql(sprintf("ALTER TABLE %s DROP CONSTRAINT %s_pkey", $table, $table));
        $this->addSql(sprintf("ALTER TABLE %s DROP COLUMN id", $table));
        $this->addSql(sprintf("ALTER TABLE %s RENAME COLUMN new_id TO id", $table));
        $this->addSql(sprintf("ALTER TABLE %s ADD PRIMARY KEY (id)", $table));

        foreach ($foreignKeys as $depTable => $foreignKeyDefinition) {
            $foreignKey = is_array($foreignKeyDefinition) ? $foreignKeyDefinition['key'] : $foreignKeyDefinition;
            $columnOldName = is_array($foreignKeyDefinition) ? $foreignKeyDefinition['column'] : sprintf('%s_%s', $table, 'id');

            $this->addSql(sprintf("ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (id)", $depTable, $foreignKey, $columnOldName, $table));
        }
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE scenario_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE scenario_step_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE step_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE step_param_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE step_part_id_seq CASCADE');

        $this->addSql('ALTER TABLE step_param ADD COLUMN new_id UUID');
        $this->addSql('UPDATE step_param SET new_id = uuid_generate_v4()');

        foreach ([
            'inline_step_param' => 'fk_13cfc238bf396750',
            'multiline_step_param' => 'fk_9c00174bf396750',
            'table_step_param' => 'fk_e536d31bbf396750'
        ] as $table => $fk) {
            $this->addSql(sprintf('ALTER TABLE %s ADD COLUMN new_id UUID', $table));
            $this->addSql(sprintf('UPDATE %s SET new_id = (SELECT new_id FROM step_param WHERE id = %s.id)', $table, $table));
            $this->addSql(sprintf('ALTER TABLE %s DROP CONSTRAINT %s', $table, $fk));
            $this->addSql(sprintf('ALTER TABLE %s DROP CONSTRAINT %s_pkey', $table, $table));
            $this->addSql(sprintf('ALTER TABLE %s DROP COLUMN id', $table));
            $this->addSql(sprintf('ALTER TABLE %s RENAME COLUMN new_id TO id', $table));
        }

        $this->convertTableId('step_param', [], false);

        foreach ([
            'inline_step_param' => 'fk_13cfc238bf396750',
            'multiline_step_param' => 'fk_9c00174bf396750',
            'table_step_param' => 'fk_e536d31bbf396750'
        ] as $table => $fk) {
            $this->addSql(sprintf('ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (id) REFERENCES step_param (id)', $table, $fk));
            $this->addSql(sprintf('ALTER TABLE %s ADD PRIMARY KEY (id)', $table));
        }

        $this->convertTableId('step_part', [
            'inline_step_param' => 'fk_13cfc238fc1ecd03'
        ]);
        $this->convertTableId('scenario_step', [
            'step_param' => [
                'key' => 'fk_b8d88b7673b21e9c',
                'column' => 'step_id'
            ]
        ]);
        $this->convertTableId('step', [
            'scenario_step' => 'fk_2374280073b21e9c',
            'step_part' => 'fk_799ed9773b21e9c',
            'step_tag' => 'fk_9197b26173b21e9c'
        ]);
        $this->convertTableId('scenario', [
            'scenario_step' => 'fk_23742800e04e49df',
            'scenario_tag' => 'fk_7ed15e1ce04e49df'
        ]);
    }

    public function down(Schema $schema): void
    {
    }
}
