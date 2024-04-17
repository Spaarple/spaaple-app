<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use DateTime;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240411103125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add created_at and updated_at if they are null in estimate and contact tables';
    }

    public function up(Schema $schema): void
    {
        $now = new DateTime();
        $formattedNow = $now->format('Y-m-d H:i:s');

        $this->addSql('UPDATE estimate SET created_at = :now, updated_at = :now WHERE created_at IS NULL OR updated_at IS NULL', ['now' => $formattedNow]);
        $this->addSql('UPDATE contact SET created_at = :now, updated_at = :now WHERE created_at IS NULL OR updated_at IS NULL', ['now' => $formattedNow]);
    }
}
