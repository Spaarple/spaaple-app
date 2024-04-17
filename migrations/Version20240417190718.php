<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240417190718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change user_client_id to user_id in estimate table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE estimate DROP FOREIGN KEY FK_D2EA4607190BE4C5');
        $this->addSql('DROP INDEX IDX_D2EA4607190BE4C5 ON estimate');
        $this->addSql('ALTER TABLE estimate CHANGE user_client_id user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA4607A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D2EA4607A76ED395 ON estimate (user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE estimate DROP FOREIGN KEY FK_D2EA4607A76ED395');
        $this->addSql('DROP INDEX IDX_D2EA4607A76ED395 ON estimate');
        $this->addSql('ALTER TABLE estimate CHANGE user_id user_client_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA4607190BE4C5 FOREIGN KEY (user_client_id) REFERENCES user_client (id)');
        $this->addSql('CREATE INDEX IDX_D2EA4607190BE4C5 ON estimate (user_client_id)');
    }
}
