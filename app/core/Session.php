<?php

namespace Imissher\Equinox\app\core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? false;

        if ($flashMessages !== false) {
            foreach ($flashMessages as &$flashMessage) {
                $flashMessage['remove'] = true;
            }

            $_SESSION[self::FLASH_KEY] = $flashMessages;
        }


    }

    public function getFlash(string $key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function setFlash(string $key, mixed $value): void
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $value
        ];
    }

    /**
     * Задает значение сессии по ключу
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Возвращает значение сессии по ключу
     *
     * @param string $key
     * @return false|mixed
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? false;
    }

    /**
     * Удаление сессии по ключу
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? false;

        if ($flashMessages !== false) {
            foreach ($flashMessages as $key => &$flashMessage) {
                if ($flashMessage['remove']) {
                    unset($flashMessages[$key]);
                }
            }

            $_SESSION[self::FLASH_KEY] = $flashMessages;
        }
    }
}