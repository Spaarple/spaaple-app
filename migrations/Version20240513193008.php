<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240513193008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add slug, subtitle and color fields to article and category tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article ADD slug VARCHAR(255) NOT NULL, ADD subtitle VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE category ADD color VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article DROP slug, DROP subtitle');
        $this->addSql('ALTER TABLE category DROP color');
    }
}
