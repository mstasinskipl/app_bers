<?php

namespace App\Controller\API;

use App\Entity\Beer;
use App\Entity\Brewer;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;

class BrewerController extends AbstractFOSRestController
{

    /**
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @Rest\Get("brewers")
     */
    public function getBrewerWithBeersAction(PaginatorInterface $paginator, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Brewer::class);

        $view = $this->view(
            $paginator->paginate($repository->findAll(),1,10),
            Response::HTTP_OK
        );

        return $this->handleView($view);
    }

}
