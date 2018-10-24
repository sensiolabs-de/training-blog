<?php

declare(strict_types = 1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController
{
    /**
     * @Route("/hello/{name}", name="hello_world")
     */
    public function helloWorld(string $name): Response
    {
        return new Response(sprintf('Hello %s!', $name));
    }
}
