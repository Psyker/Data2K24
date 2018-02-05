<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SensioLabs\Security\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/api")
 */
class AuthenticationController extends FOSRestController
{

    /**
     * @Rest\Post("/authenticate")
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $apiKey = $request->request->get('apiKey');
        $apiSecret = $request->request->get('apiSecret');

        /** @var User $user */
        $user = $em->getRepository('AppBundle:User')->findOneByApiKey($apiKey);
        if (!$user || $apiSecret != $user->getApiSecret()) {
            throw new HttpException('You are not allowed to call this endpoint');
        }

        $jwtManager = $this->get('app.jwt_manager');
        $token = [
            'iat' => (new \DateTime())->getTimestamp(),
            'username' => $user->getUsername(),
        ];

        return new JsonResponse($jwtManager->encode($token));
    }

}