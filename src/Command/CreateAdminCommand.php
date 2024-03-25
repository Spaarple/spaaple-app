<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User\UserAdministrator;
use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

#[AsCommand(name: 'app:create-admin', description: 'Create a new administrator')]
class CreateAdminCommand extends Command
{
    /**
     * @param LoggerInterface             $logger
     * @param EntityManagerInterface      $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Création d\'un administrateur');

        $firstname = $io->ask(
            question: 'Prénom de l\'administrateur',
            validator: $this->createValidator(new NotBlank(message: 'Le prénom ne peut être vide.')),
        );
        $lastname = $io->ask(
            question: 'Nom de l\'administrateur',
            validator: $this->createValidator(new NotBlank(message: 'Le nom ne peut être vide.'))
        );
        $email = $io->ask(
            question: 'Email de l\'administrateur',
            validator: $this->createValidator(
                new NotBlank(message: 'L\'email ne peut être vide.'),
                new Email(message: 'Email invalide.')
            )
        );
        $password = $io->askHidden(
            question: 'Mot de passe de l\'administrateur.',
            validator: $this->createValidator(
                new NotBlank(message: 'Le mot de passe de ne peut être vide.'),
                new NotCompromisedPassword(message: 'Le mot de passe est compromis.'),
                new PasswordStrength(
                    minScore: 3,
                    message: 'Your password is too easy to guess. Company\'s security policy requires to use a stronger password.'
                )
            )
        );

        $admin = new UserAdministrator();
        $admin->setEmail($email);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $password));
        $admin->setFirstName($firstname);
        $admin->setLastName($lastname);
        $admin->setRoles([Role::ROLE_ADMINISTRATOR->name]);

        try {
            $this->entityManager->persist($admin);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            $io->error('Impossible de créer l\'administrateur.');

            throw $e;
        }

        $io->success('Administrateur créé.');

        return Command::SUCCESS;
    }

    /**
     * @param Constraint ...$constraints
     *
     * @return callable
     */
    private function createValidator(Constraint ...$constraints): callable
    {
        return static function ($str) use ($constraints) {
            try {
                Validation::createCallable(...$constraints)($str);

                return $str;
            } catch (ValidationFailedException $e) {
                $err = '';

                foreach ($e->getViolations() as $violation) {
                    $err .= $violation->getMessage() . ' ';
                }

                throw new RuntimeException($err, previous: $e);
            }
        };
    }
}
