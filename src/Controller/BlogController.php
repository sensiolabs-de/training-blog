<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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

    public function commentForm(Post $post)
    {
        $form = $this->createFormBuilder();
        $form
            ->add('authorEmail', EmailType::class)
            ->add('content',TextareaType::class, [
                'attr' => ['rows' => 10],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Publish a comment',
                'attr' => ['class' => 'btn-primary pull-right'],
            ])
        ;

        return $this->render('blog/_comment_form.html.twig', array(
            'post' => $post,
            'form' => $form->getForm()->createView(),
        ));
    }
}
