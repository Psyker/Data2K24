<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class HeatController extends FOSRestController
{

    /**
     * @Rest\Get("/heat")
     */
    public function getHeatMap()
    {

    }
}
