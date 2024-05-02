<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240502083802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update CMS enum values in estimate table';
    }

    public function up(Schema $schema): void
    {
        $cmsOld = ['Site sur mesure', 'Shopify', 'Wordpress'];

        $cmsNew = [
            'Création personnalisée du site',
            'Plateforme de commerce en ligne prête à l\'emploi (e-commerce)',
            'Outil de création de site web simple et populaire',
        ];

        for ($i = 0, $iMax = count($cmsOld); $i < $iMax; $i++) {
            $this->addSql(
                'UPDATE estimate SET cms = :newCms WHERE cms = :oldCms',
                ['newCms' => $cmsNew[$i], 'oldCms' => $cmsOld[$i]]
            );
        }
    }

    public function down(Schema $schema): void
    {
        $cmsOld = ['Site sur mesure', 'Shopify', 'Wordpress'];

        $cmsNew = [
            'Création personnalisée du site',
            'Plateforme de commerce en ligne prête à l\'emploi (e-commerce)',
            'Outil de création de site web simple et populaire',
        ];

        for ($i = 0, $iMax = count($cmsOld); $i < $iMax; $i++) {
            $this->addSql(
                'UPDATE estimate SET cms = :oldCms WHERE cms = :newCms',
                ['oldCms' => $cmsOld[$i], 'newCms' => $cmsNew[$i]]
            );
        }
    }
}