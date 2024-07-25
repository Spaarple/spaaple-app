<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240514161510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Image field added to article table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article ADD image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article DROP image');
    }
}
