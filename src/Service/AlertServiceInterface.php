<?php

declare(strict_types=1);

namespace App\Service;

interface AlertServiceInterface
{
    /**
     * Success alert.
     *
     * @param string $message
     */
    public function success(string $message): void;

    /**
     * Danger alert.
     *
     * @param string $message
     */
    public function error(string $message): void;

    /**
     * Warning alert.
     *
     * @param string $message
     */
    public function warning(string $message): void;

    /**
     * Info alert.
     *
     * @param string $message
     */
    public function info(string $message): void;
}
