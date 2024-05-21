<?php

namespace App\Controller\Admin;

use App\Entity\BulkContact;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Flasher\Prime\FlasherInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class BulkContactCrudController extends AbstractCrudController
{
    /**
     * @param FlasherInterface $flasher
     * @param MailerInterface $mailer
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private readonly FlasherInterface $flasher,
        private readonly MailerInterface $mailer,
        private readonly ParameterBagInterface $parameterBag,
    )
    {
    }

    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return BulkContact::class;
    }

    /**
     * @param Crud $crud
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Gestion des envois de masse')
            ->setPageTitle('detail', 'Détail du mail');
    }

    /**
     * @param Actions $actions
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT);
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            ArrayField::new('email', 'Destinataires'),
            TextField::new('subject', 'Sujet'),
            TextEditorField::new('message', 'Message Commun')
                ->setNumOfRows(20) ->formatValue(function ($value) {
                    return nl2br($value);
                }),
            DateTimeField::new('createdAt')->setLabel('Date de création')->onlyOnIndex(),
        ];
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     * @return void
     * @throws TransportExceptionInterface
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        foreach ($entityInstance->getEmail() as $email) {
            $email = (new TemplatedEmail())
                ->from(new Address($this->parameterBag->get('mail.support'), 'Spaarple'))
                ->to($email)
                ->subject($entityInstance->getSubject())
                ->textTemplate('admin/email/bulk_contact.txt.twig')
                ->htmlTemplate('admin/email/bulk_contact.html.twig')
                ->context([
                    'message' => $entityInstance->getMessage(),
                ]);

            $this->mailer->send($email);
        }

        $this->flasher->success('Message envoyé avec succès !');

        parent::persistEntity($entityManager, $entityInstance);
    }


}
