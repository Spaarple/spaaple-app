<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240808150223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Bulk contact email fields type change';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bulk_contact CHANGE email email LONGTEXT NOT NULL, CHANGE expeditor expeditor VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bulk_contact CHANGE email email JSON NOT NULL, CHANGE expeditor expeditor TINYTEXT NOT NULL');
    }
}
