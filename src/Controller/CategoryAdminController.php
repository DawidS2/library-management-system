<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryAdminController extends AbstractController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * CategoryAdminController constructor.
     * @param RequestStack $request
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->request = $request->getCurrentRequest();
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }


    /**
     * List of all categories
     *
     * @Route("/admin/category", methods="GET", name="admin_category_index")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $page = $this->request->query->getInt('page', 1);

        $categories = $categoryRepository->findAll();
        $categories = $this->paginator->paginate($categories, $page, 10);

        return $this->render('category_admin/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * New category
     *
     * @Route("admin/category/new", methods="GET|POST", name="admin_category_new")
     */
    public function new()
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Category $data */
            $data = $form->getData();

            $this->entityManager->persist($data);
            $this->entityManager->flush();

            $this->addFlash('success', sprintf("Pomyślnie dodano rekord o ID: %d", $data->getId()));

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('category_admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit category
     *
     * @Route("admin/category/{id<\d+>}/edit", methods="GET|POST", name="admin_category_edit")
     * @param Category $category
     * @return RedirectResponse|Response
     */
    public function edit(Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', sprintf("Pomyślnie zmieniono rekord o ID: %d", $category->getId()));

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('category_admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
