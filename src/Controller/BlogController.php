<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="blog_")
 */
class BlogController extends Controller
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

    /**
     * @Route("{slug}/comment/new", name="comment_new")
     */
    public function commentNew(Request $request, Post $post): Response
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();
            $comment->setPost($post);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('blog_post', ['slug' => $post->getSlug()]);
        }

        return $this->render('blog/comment_form_error.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    public function commentForm(Post $post)
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('blog/_comment_form.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }
}
