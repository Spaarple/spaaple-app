<?php

declare(strict_types=1);

namespace App\Helper;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GeneratePasswordHelper implements GeneratePasswordInterfaceHelper
{

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param MailerInterface $mailer
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly MailerInterface $mailer,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public static function generatePassword(int $length): string
    {
        return mb_substr(sha1((string) time()), 0, $length);
    }

    /**
     * @param $entityInstance
     * @return void
     * @throws TransportExceptionInterface
     */
    public function createAccount($entityInstance): void
    {
        $generatePassword = self::generatePassword(10);

        $entityInstance->setPassword(
            $this->passwordHasher->hashPassword($entityInstance, $generatePassword)
        );

        $email = (new TemplatedEmail())
            ->from(new Address($this->parameterBag->get('mail.support'), 'Création de votre compte'))
            ->to($entityInstance->getEmail())
            ->subject('Votre compte a été créé avec succès')
            ->htmlTemplate('register/email/email.html.twig')
            ->context([
                'you' => sprintf('%s %s', $entityInstance->getFirstName(), $entityInstance->getLastName()),
                'generatePassword' => $generatePassword,
            ]);

        $this->mailer->send($email);
    }
}
