<?php

declare(strict_types=1);

namespace App\Controllers;

use Faker\Factory;
use Nitogram\Foundation\AbstractController;

class BaseController extends AbstractController
{
    public function index(): void
    {
        $faker = Factory::create();
        echo "<h1>I love $faker->city !! </h1>";
    }

}