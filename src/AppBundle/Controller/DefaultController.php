<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class DefaultController extends FOSRestController
{
    public function getTimestamps($name)
    {
        return $this->render('', array('name' => $name));
    }
}
