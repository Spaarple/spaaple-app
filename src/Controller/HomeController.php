<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_home')]
class HomeController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @return Response
     */
    #[Route('/mentions-legales', name: '_mentions_legales')]
    public function mentionsLegals(): Response
    {
        return $this->render('home/mentions_legales.html.twig');
    }

    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/blog', name: '_blog')]
    public function blog(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([
            'isPublished' => true
        ]);

        return $this->render('home/blog.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @param $slug
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('blog/{slug}', name: '_article', methods: ['GET'])]
    public function show($slug, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBy([
            'slug' => $slug
        ]);

        $articleSameCategory = $articleRepository->findBy([
            'category' => $article?->getCategory(),
        ]);

        $similarArticles = array_filter($articleSameCategory, static function($otherArticle) use ($article) {
            return $otherArticle->getId() !== $article->getId();
        });

        $similarArticles = array_slice($similarArticles, 0, 4);
;
        return $this->render('home/article.html.twig',[
            'article' => $article,
            'similar_articles' => $similarArticles
        ]);
    }

}
