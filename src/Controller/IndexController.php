<?php

namespace App\Controller;
use App\Entity\CategorySearch;
use App\Entity\PriceSearch;
use App\Form\CategorySearchType;
use App\Form\PriceSearchType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_article_index');
    }

    #[Route('/article', name: 'app_article_index', methods: ['GET'])]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);
    
        $articles = $articleRepository->findAll(); // Default to all articles
    
        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $search->getNom();
            if ($nom) {
                $articles = $articleRepository->findByNom($nom); // Custom repository method
            }
        }
    
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'form' => $form->createView(),
        ]);
    }
   
    #[Route('/article/new', name: 'app_article_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('app_article_index');
        } catch (\Exception $e) {
            // Simple error handling, no flash message that might cause session issues
            return $this->render('articles/new.html.twig', [
                'article' => $article,
                'form' => $form->createView(),
                'error' => $e->getMessage()
            ]);
        }
    }

    return $this->render('articles/new.html.twig', [
        'article' => $article,
        'form' => $form->createView(),
    ]);
}

    #[Route('/article/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/edit/{id}', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article, [
            'csrf_token_id' => 'edit_article_form' // Use a different token ID for edit form
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                return $this->redirectToRoute('app_article_index');
            } catch (\Exception $e) {
                // Simple error handling
                return $this->render('articles/edit.html.twig', [
                    'article' => $article,
                    'form' => $form->createView(),
                    'error' => $e->getMessage()
                ]);
            }
        }
    
        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/delete/{id}', name: 'app_article_delete')]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_article_index');
    }

    // Category management methods

    #[Route('/category/newCat', name: 'new_category', methods: ['GET', 'POST'])]
    public function newCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index');
        }

        return $this->render('articles/newCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categories', name: 'category_list', methods: ['GET'])]
    public function categoryList(CategoryRepository $categoryRepository): Response
    {
        return $this->render('articles/categories.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }
    #[Route('/article/search/category', name: 'app_article_search_category', methods: ['GET'])]
    public function searchByCategory(Request $request, ArticleRepository $articleRepository): Response
    {
        $search = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class, $search);
        $form->handleRequest($request);

        $articles = $articleRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $search->getCategory();
            if ($category) {
                $articles = $articleRepository->findBy(['category' => $category]);
            }
        }

        return $this->render('articles/search_by_category.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
        ]);
    }
    #[Route('/article/search/price', name: 'app_article_search_price', methods: ['GET'])]
    public function searchByPrice(Request $request, ArticleRepository $articleRepository): Response
    {
        $search = new PriceSearch();
        $form = $this->createForm(PriceSearchType::class, $search);
        $form->handleRequest($request);

        $articles = $articleRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $minPrice = $search->getMinPrice();
            $maxPrice = $search->getMaxPrice();
            $articles = $articleRepository->findByPriceRange($minPrice, $maxPrice);
        }

        return $this->render('articles/search_by_price.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
        ]);
    }
}