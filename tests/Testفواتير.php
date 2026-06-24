<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\FacturesController;
use App\Repository\FacturesRepository;
use App\Entity\Factures;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestFactures extends TestCase
{
    private $facturesController;
    private $facturesRepository;

    protected function setUp(): void
    {
        $this->facturesRepository = Mockery::mock(FacturesRepository::class);
        $this->facturesController = new FacturesController($this->facturesRepository);
    }

    public function testGetAllFactures()
    {
        $factures = [
            new Factures('facture1', 'client1', 'date1'),
            new Factures('facture2', 'client2', 'date2'),
        ];

        $this->facturesRepository->shouldReceive('findAll')->andReturn($factures);

        $response = $this->facturesController->getAllFactures();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($factures), $response->getContent());
    }

    public function testGetFactureById()
    {
        $facture = new Factures('facture1', 'client1', 'date1');

        $this->facturesRepository->shouldReceive('find')->with(1)->andReturn($facture);

        $response = $this->facturesController->getFactureById(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($facture), $response->getContent());
    }

    public function testCreateFacture()
    {
        $facture = new Factures('facture1', 'client1', 'date1');

        $this->facturesRepository->shouldReceive('save')->with($facture)->andReturn($facture);

        $request = new Request();
        $request->request->set('name', 'facture1');
        $request->request->set('client', 'client1');
        $request->request->set('date', 'date1');

        $response = $this->facturesController->createFacture($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($facture), $response->getContent());
    }

    public function testUpdateFacture()
    {
        $facture = new Factures('facture1', 'client1', 'date1');

        $this->facturesRepository->shouldReceive('find')->with(1)->andReturn($facture);
        $this->facturesRepository->shouldReceive('save')->with($facture)->andReturn($facture);

        $request = new Request();
        $request->request->set('name', 'facture2');
        $request->request->set('client', 'client2');
        $request->request->set('date', 'date2');

        $response = $this->facturesController->updateFacture(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($facture), $response->getContent());
    }

    public function testDeleteFacture()
    {
        $facture = new Factures('facture1', 'client1', 'date1');

        $this->facturesRepository->shouldReceive('find')->with(1)->andReturn($facture);
        $this->facturesRepository->shouldReceive('remove')->with($facture);

        $response = $this->facturesController->deleteFacture(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// FacturesController.php

namespace App\Controller;

use App\Repository\FacturesRepository;
use App\Entity\Factures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FacturesController
{
    private $facturesRepository;

    public function __construct(FacturesRepository $facturesRepository)
    {
        $this->facturesRepository = $facturesRepository;
    }

    public function getAllFactures()
    {
        $factures = $this->facturesRepository->findAll();
        return new Response(json_encode($factures));
    }

    public function getFactureById($id)
    {
        $facture = $this->facturesRepository->find($id);
        return new Response(json_encode($facture));
    }

    public function createFacture(Request $request)
    {
        $facture = new Factures($request->request->get('name'), $request->request->get('client'), $request->request->get('date'));
        $this->facturesRepository->save($facture);
        return new Response(json_encode($facture), Response::HTTP_CREATED);
    }

    public function updateFacture($id, Request $request)
    {
        $facture = $this->facturesRepository->find($id);
        $facture->setName($request->request->get('name'));
        $facture->setClient($request->request->get('client'));
        $facture->setDate($request->request->get('date'));
        $this->facturesRepository->save($facture);
        return new Response(json_encode($facture));
    }

    public function deleteFacture($id)
    {
        $facture = $this->facturesRepository->find($id);
        $this->facturesRepository->remove($facture);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}