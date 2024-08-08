<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240808144047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add expeditor field to bulk_contact table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bulk_contact ADD expeditor TINYTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bulk_contact DROP expeditor');
    }
}
