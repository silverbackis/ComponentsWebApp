<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use ApiPlatform\Core\Exception\RuntimeException;
use App\Entity\Component\Form\Form;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectRepository;
use Limenius\Liform\Liform;
use Symfony\Component\Form\FormFactoryInterface;

class FormDataProvider implements ItemDataProviderInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ObjectRepository
     */
    private $managerRegistry;

    /**
     * @var Liform
     */
    private $liform;

    /**
     * FormDataProvider constructor.
     * @param FormFactoryInterface $formFactory
     * @param ManagerRegistry $managerRegistry
     * @param Liform $liform
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ManagerRegistry $managerRegistry,
        Liform $liform
    )
    {
        $this->formFactory = $formFactory;
        $this->managerRegistry = $managerRegistry;
        $this->liform = $liform;
    }

    /**
     * @param string $resourceClass
     * @param $id
     * @param string|null $operationName
     * @param array $context
     *
     * @return null|Form
     * @throws ResourceClassNotSupportedException
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $manager = $this->managerRegistry->getManagerForClass($resourceClass);
        if (null === $manager) {
            throw new ResourceClassNotSupportedException();
        }

        if (Form::class !== $resourceClass) {
            throw new ResourceClassNotSupportedException();
        }

        $repository = $manager->getRepository($resourceClass);
        if (!method_exists($repository, 'find')) {
            throw new RuntimeException('The repository class must have a "find" method.');
        }

        /**
         * @var null|Form $resource
         */
        $resource = $repository->find($id);

        return $resource;
    }
}
