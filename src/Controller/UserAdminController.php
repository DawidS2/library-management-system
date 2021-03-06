<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdminController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * UserAdminController constructor.
     * @param RequestStack $request
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;
        $this->request = $request->getCurrentRequest();
        $this->paginator = $paginator;
    }


    /**
     * @Route("/admin/user", name="admin_user_index")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository)
    {
        $page = $this->request->query->getInt('page', 1);

        $users = $userRepository->findAll();
        $users = $this->paginator->paginate($users, $page, 10);

        return $this->render('user_admin/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/user/{id<\d+>}", methods="GET|POST", name="admin_user_edit")
     * @param User $user
     * @return RedirectResponse|Response
     */
    public function edit(User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Pomyślnie zmodyfikowano użytkownika');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('user_admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
