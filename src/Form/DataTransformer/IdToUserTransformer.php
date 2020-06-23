<?php


namespace App\Form\DataTransformer;


use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToUserTransformer implements DataTransformerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * IdToUserTransformer constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    public function reverseTransform($value)
    {
        $user = $this->userRepository->find($value);

        if (null === $user) {
            throw new TransformationFailedException("Użytkownik nie istnieje");
        }

        return $user;
    }
}