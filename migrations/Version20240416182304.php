<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240416182304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add email field to estimate table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE estimate ADD email VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE estimate DROP email');
    }
}
