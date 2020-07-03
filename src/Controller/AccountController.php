<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * Class AccountController
 * @package App\Controller
 */
class AccountController extends AbstractController
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
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AccountController constructor.
     * @param EntityManagerInterface $entityManager
     * @param RequestStack $request
     */
    public function __construct(EntityManagerInterface $entityManager, RequestStack $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->request = $request->getCurrentRequest();
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * @Route("/account", name="account_edit_data")
     */
    public function editData()
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Pomyślnie zmieniono dane!');
        }

        return $this->render('account/edit_data.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/account/change-password", methods="GET|POST", name="account_change_password")
     */
    public function changePassword()
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $oldPassword = $data['oldPassword'];
            $newPassword = $data['newPassword']['password'];
            /** @var User $user */
            $user = $this->getUser();

            if ($this->passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));
                $this->addFlash('success', 'Pomyślnie zmieniono hasło');
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $this->redirectToRoute('book_index');
            }else{
                $this->addFlash('danger', 'Hasło niepoprawne');
            }
        }

        return $this->render('account/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
