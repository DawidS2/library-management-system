<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

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
     * AccountController constructor.
     * @param EntityManagerInterface $entityManager
     * @param RequestStack $request
     */
    public function __construct(EntityManagerInterface $entityManager, RequestStack $request)
    {
        $this->entityManager = $entityManager;
        $this->request = $request->getCurrentRequest();
    }


    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
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
}
