<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use SensioLabs\Security\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class AuthenticationController extends FOSRestController
{

    /**
     * @Rest\Post("/authenticate")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the JWT needed to call every other endpoints.",
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Unauthorized access : Invalid apiKey or apiSecret"
     * )
     * @SWG\Parameter(
     *     name="apiKey",
     *     required=true,
     *     in="formData",
     *     type="string",
     *     description="The user apiKey",
     * )
     * @SWG\Parameter(
     *     name="apiSecret",
     *     required=true,
     *     in="formData",
     *     type="string",
     *     description="The user apiSecret",
     * )
     * @SWG\Tag(name="Authentication")
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