<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;

class UtilsController extends FOSRestController
{

    /**
     * @Rest\Get("/timestamps")
     * @SWG\Response(
     *     response=200,
     *     description="Returns an array of 2-hour periods on 16 days",
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Tag(name="Utils")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTimestamps()
    {
        $timestamps = $this->get('app.time_service')->getTimestamps();
        $payload = [
            'timestamps' => []
        ];

        /** @var \DateTime $timestamp */
        foreach ($timestamps as $timestamp) {
            array_push($payload['timestamps'], $timestamp->getTimestamp());
        }

        return new JsonResponse($payload);
    }
}
