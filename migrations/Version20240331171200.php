<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240331171200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change TYPE of Result in estimate table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE estimate CHANGE result result JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE estimate CHANGE result result SMALLINT NOT NULL');
    }
}
