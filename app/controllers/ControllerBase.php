<?php
declare(strict_types=1);

namespace App\Controllers;

/**
 * Simple base controller (tanpa dependency Phalcon extension)
 * Dipakai oleh controller generik seperti IndexController.
 */
class ControllerBase
{
    /** @var mixed */
    protected $di;

    /**
     * Disuntik dari front controller (public/index.php)
     */
    public function _setDi($di): void
    {
        $this->di = $di;
    }
}
