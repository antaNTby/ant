<?php

declare(strict_types=1);

namespace app\controllers;

use flight\Engine;

class BaseSubController
{
    /** @var Engine */
    protected Engine $app;

    /**
     * Constructor
     */
    public function __construct(Engine $app)
    {
        $this->app = $app;
    }
}
