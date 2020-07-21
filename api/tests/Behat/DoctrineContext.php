<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behatch\Context\RestContext as BehatchRestContext;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ObjectManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class DoctrineContext implements Context
{
    private ManagerRegistry $doctrine;
    private RestContext $restContext;
    private ?BehatchRestContext $baseRestContext;
    private JWTTokenManagerInterface $jwtManager;
    private IriConverterInterface $iriConverter;
    private ObjectManager $manager;
    private SchemaTool $schemaTool;
    private UserPasswordEncoderInterface $passwordEncoder;
    private array $classes;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(
        ManagerRegistry $doctrine,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenManagerInterface $jwtManager,
        IriConverterInterface $iriConverter
    ) {
        $this->doctrine = $doctrine;
        $this->manager = $doctrine->getManager();
        $this->schemaTool = new SchemaTool($this->manager);
        $this->classes = $this->manager->getMetadataFactory()->getAllMetadata();
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtManager = $jwtManager;
        $this->iriConverter = $iriConverter;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $this->baseRestContext = $scope->getEnvironment()->getContext(BehatchRestContext::class);
        $this->restContext = $scope->getEnvironment()->getContext(RestContext::class);
    }

    /**
     * @BeforeScenario
     */
    public function createDatabase(): void
    {
        $this->schemaTool->dropSchema($this->classes);
        $this->doctrine->getManager()->clear();
        $this->schemaTool->createSchema($this->classes);
    }

    private function login(array $roles = []): void
    {
        $user = new User();
        $user
            ->setRoles($roles)
            ->setUsername('user@example.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'password'))
            ->setEnabled(true);
        $this->manager->persist($user);
        $this->manager->flush();

        $token = $this->jwtManager->create($user);
        $this->baseRestContext->iAddHeaderEqualTo('Authorization', "Bearer $token");
        $this->manager->clear();
    }

    /**
     * @BeforeScenario @loginSuperAdmin
     */
    public function loginSuperAdmin(BeforeScenarioScope $scope): void
    {
        $this->login(['ROLE_SUPER_ADMIN']);
    }

    /**
     * @BeforeScenario @loginAdmin
     */
    public function loginAdmin(BeforeScenarioScope $scope): void
    {
        $this->login(['ROLE_ADMIN']);
    }

    /**
     * @BeforeScenario @loginUser
     */
    public function loginUser(BeforeScenarioScope $scope): void
    {
        $this->login(['ROLE_USER']);
    }

    /**
     * @AfterScenario
     */
    public function logout(): void
    {
        $this->baseRestContext->iAddHeaderEqualTo('Authorization', '');
    }
}
