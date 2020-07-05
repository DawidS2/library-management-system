<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * RegistrationController constructor.
     * @param RequestStack $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(RequestStack $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->request = $request->getCurrentRequest();
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }


    /**
     * User registration
     *
     * @Route("/register", methods="GET|POST", name="register")
     */
    public function register(): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $password = $form['plainPassword']->getData()['password'];

            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Rejestracja przebiegła pomyślnie');

            return $this->redirectToRoute('book_index');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
