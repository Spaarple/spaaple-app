<?php

declare(strict_types=1);

namespace App\Service;

readonly class AlertService implements AlertServiceInterface
{

    /**
     * @param string $message
     * @return void
     */
    public function success(string $message): void
    {
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->duration(2000)
            ->addSuccess($message,null, ['background' => '#4C956C']);
    }

    /**
     * @param string $message
     * @return void
     */
    public function error(string $message): void
    {
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->duration(2000)
            ->addError($message,null, ['background' => '#EF233C']);
    }

    /**
     * @param string $message
     * @return void
     */
    public function warning(string $message): void
    {
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->duration(2000)
            ->addWarning($message,null, ['background' => '#FAA307']);
    }

    /**
     * @param string $message
     * @return void
     */
    public function info(string $message): void
    {
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->duration(2000)
            ->addInfo($message,null, ['background' => '#83C5BE']);
    }
}
