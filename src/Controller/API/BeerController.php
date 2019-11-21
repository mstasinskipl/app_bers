<?php

namespace App\Controller\API;

use App\Entity\Beer;
use App\Entity\Brewer;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;

class BeerController extends AbstractFOSRestController
{

    /**
     * @param $id
     * @return Response
     * @Rest\Get("beer/{id}")
     */
    public function getBeerAction(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);

        $beer = $repository->find($id);
        $view = $this->view($beer, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @Rest\Get("beers")
     */
    public function getBeersAction(PaginatorInterface $paginator, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Beer::class);
        $repositoryBrewer = $this->getDoctrine()->getRepository(Brewer::class);

        $view = $this->view(
            $paginator->paginate($repository->findBy(
                [],
                ['name' => 'DESC']),
                $request->query->get('page') ?? 1,10),
            Response::HTTP_OK
        );

        return $this->handleView($view);
    }

}
