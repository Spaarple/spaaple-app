<?php

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    #[SecurityAssert\UserPassword(message: 'Mot de passe incorrect')]
    protected string $oldPassword;

    #[Assert\Length(min: 8, max: 50)]
    protected string $newPassword;

    /**
     * @return string
     */
    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    /**
     * @param string $oldPassword
     *
     * @return string
     */
    public function setOldPassword(string $oldPassword): string
    {
        return $this->oldPassword = $oldPassword;

    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     *
     * @return string
     */
    public function setNewPassword(string $newPassword): string
    {
        return $this->newPassword = $newPassword;
    }
}
