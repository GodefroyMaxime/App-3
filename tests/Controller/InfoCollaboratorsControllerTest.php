<?php

namespace App\Test\Controller;

use App\Entity\InfoCollaborators;
use App\Repository\InfoCollaboratorsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InfoCollaboratorsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private InfoCollaboratorsRepository $repository;
    private string $path = '/info/collaborators/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(InfoCollaborators::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('InfoCollaborator index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'info_collaborator[matricule]' => 'Testing',
            'info_collaborator[name]' => 'Testing',
            'info_collaborator[firstname]' => 'Testing',
            'info_collaborator[position]' => 'Testing',
            'info_collaborator[seniority]' => 'Testing',
            'info_collaborator[gross_annual_salary]' => 'Testing',
            'info_collaborator[net_annual_salary]' => 'Testing',
            'info_collaborator[bonus]' => 'Testing',
            'info_collaborator[employee_share_mutual_insurance]' => 'Testing',
            'info_collaborator[employer_share_mutual_insurance]' => 'Testing',
            'info_collaborator[employee_share_health_insurance]' => 'Testing',
            'info_collaborator[employer_share_health_insurance]' => 'Testing',
            'info_collaborator[employee_share_pension]' => 'Testing',
            'info_collaborator[employer_share_pension]' => 'Testing',
            'info_collaborator[csp]' => 'Testing',
        ]);

        self::assertResponseRedirects('/info/collaborators/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new InfoCollaborators();
        $fixture->setMatricule('My Title');
        $fixture->setName('My Title');
        $fixture->setFirstname('My Title');
        $fixture->setPosition('My Title');
        $fixture->setSeniority('My Title');
        $fixture->setGross_annual_salary('My Title');
        $fixture->setNet_annual_salary('My Title');
        $fixture->setBonus('My Title');
        $fixture->setEmployee_share_mutual_insurance('My Title');
        $fixture->setEmployer_share_mutual_insurance('My Title');
        $fixture->setEmployee_share_health_insurance('My Title');
        $fixture->setEmployer_share_health_insurance('My Title');
        $fixture->setEmployee_share_pension('My Title');
        $fixture->setEmployer_share_pension('My Title');
        $fixture->setCsp('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('InfoCollaborator');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new InfoCollaborators();
        $fixture->setMatricule('My Title');
        $fixture->setName('My Title');
        $fixture->setFirstname('My Title');
        $fixture->setPosition('My Title');
        $fixture->setSeniority('My Title');
        $fixture->setGross_annual_salary('My Title');
        $fixture->setNet_annual_salary('My Title');
        $fixture->setBonus('My Title');
        $fixture->setEmployee_share_mutual_insurance('My Title');
        $fixture->setEmployer_share_mutual_insurance('My Title');
        $fixture->setEmployee_share_health_insurance('My Title');
        $fixture->setEmployer_share_health_insurance('My Title');
        $fixture->setEmployee_share_pension('My Title');
        $fixture->setEmployer_share_pension('My Title');
        $fixture->setCsp('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'info_collaborator[matricule]' => 'Something New',
            'info_collaborator[name]' => 'Something New',
            'info_collaborator[firstname]' => 'Something New',
            'info_collaborator[position]' => 'Something New',
            'info_collaborator[seniority]' => 'Something New',
            'info_collaborator[gross_annual_salary]' => 'Something New',
            'info_collaborator[net_annual_salary]' => 'Something New',
            'info_collaborator[bonus]' => 'Something New',
            'info_collaborator[employee_share_mutual_insurance]' => 'Something New',
            'info_collaborator[employer_share_mutual_insurance]' => 'Something New',
            'info_collaborator[employee_share_health_insurance]' => 'Something New',
            'info_collaborator[employer_share_health_insurance]' => 'Something New',
            'info_collaborator[employee_share_pension]' => 'Something New',
            'info_collaborator[employer_share_pension]' => 'Something New',
            'info_collaborator[csp]' => 'Something New',
        ]);

        self::assertResponseRedirects('/info/collaborators/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getMatricule());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getFirstname());
        self::assertSame('Something New', $fixture[0]->getPosition());
        self::assertSame('Something New', $fixture[0]->getSeniority());
        self::assertSame('Something New', $fixture[0]->getGross_annual_salary());
        self::assertSame('Something New', $fixture[0]->getNet_annual_salary());
        self::assertSame('Something New', $fixture[0]->getBonus());
        self::assertSame('Something New', $fixture[0]->getEmployee_share_mutual_insurance());
        self::assertSame('Something New', $fixture[0]->getEmployer_share_mutual_insurance());
        self::assertSame('Something New', $fixture[0]->getEmployee_share_health_insurance());
        self::assertSame('Something New', $fixture[0]->getEmployer_share_health_insurance());
        self::assertSame('Something New', $fixture[0]->getEmployee_share_pension());
        self::assertSame('Something New', $fixture[0]->getEmployer_share_pension());
        self::assertSame('Something New', $fixture[0]->getCsp());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new InfoCollaborators();
        $fixture->setMatricule('My Title');
        $fixture->setName('My Title');
        $fixture->setFirstname('My Title');
        $fixture->setPosition('My Title');
        $fixture->setSeniority('My Title');
        $fixture->setGross_annual_salary('My Title');
        $fixture->setNet_annual_salary('My Title');
        $fixture->setBonus('My Title');
        $fixture->setEmployee_share_mutual_insurance('My Title');
        $fixture->setEmployer_share_mutual_insurance('My Title');
        $fixture->setEmployee_share_health_insurance('My Title');
        $fixture->setEmployer_share_health_insurance('My Title');
        $fixture->setEmployee_share_pension('My Title');
        $fixture->setEmployer_share_pension('My Title');
        $fixture->setCsp('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/info/collaborators/');
    }
}
