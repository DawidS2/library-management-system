<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * UserAdminController constructor.
     * @param RequestStack $request
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->request = $request->getCurrentRequest();
    }


    /**
     * @Route("/admin/user", name="admin_user_index")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('user_admin/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/user/{id<\d+>}", methods="GET|POST", name="admin_user_edit")
     * @param User $user
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
