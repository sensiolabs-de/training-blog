<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="blog_")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @Route("/{page}", name="index_paged", requirements={"page":"\d+"})
     */
    public function index($page = 1): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findLatest($page);

        return $this->render('index.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("{slug}", name="post")
     */
    public function show(Post $post): Response
    {
        return $this->render('blog/show.html.twig', ['post' => $post]);
    }
}
