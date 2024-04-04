<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use DateTime;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240404075546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update user table with created_at and updated_at fields with current timestamp if they are null';
    }

    public function up(Schema $schema): void
    {
        $now = new DateTime();
        $formattedNow = $now->format('Y-m-d H:i:s');

        $this->addSql('UPDATE user SET created_at = :now, updated_at = :now WHERE created_at IS NULL OR updated_at IS NULL', ['now' => $formattedNow]);
    }

}
