<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

class HeatController extends FOSRestController
{

    /**
     * @Rest\Get("/heat")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the entire heat map.",
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Tag(name="Heat Map")
     */
    public function getHeatMap()
    {

    }
}
