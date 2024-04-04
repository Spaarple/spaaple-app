<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240328133726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add description, description_page and reference to estimate table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE estimate ADD description LONGTEXT NOT NULL, ADD description_page LONGTEXT NOT NULL, ADD reference LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE estimate DROP description, DROP description_page, DROP reference');
    }
}
