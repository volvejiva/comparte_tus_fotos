<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

class ApiController extends FOSRestController
{
    /**
     * @Get("/list")
     **/
    public function listAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repositorioAlbum = $entityManager->getRepository("AppBundle:Album");
        $albums = $repositorioAlbum->findAll();
        
        $view = $this->view($albums, 200);
        return $this->handleView($view);
    }
}