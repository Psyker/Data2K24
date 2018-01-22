<?php

namespace AppBundle\Controller\Front;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ApiController extends Controller
{

    /**
     * @Route("/hello", name="test_route")
     */
    public function getHelloAction()
    {
        return new JsonResponse('test');
    }

    /**
     * @Rest\Get("/users", name="app_get_users")
     */
    public function getUsersAction()
    {
        try {
            $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();
            if (empty($users)) {
                throw new NotFoundHttpException("There is no users");
            }
        } catch (NotFoundHttpException $e) {
            return JsonResponse::create([
                'message' => $e->getMessage(),
                'code' => $e->getStatusCode()
            ])->setStatusCode($e->getStatusCode());
        }

        return ['users' => $users];
    }


}
