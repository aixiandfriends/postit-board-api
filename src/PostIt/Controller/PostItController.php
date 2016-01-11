<?php

namespace PostIt\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostItController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $content = iterator_to_array($this->get('postit.mongodb_client')->select());

        return new JsonResponse($content);
    }

    /**
     * @param Request $request
     */
    public function showAction(Request $request)
    {

    }

    /**
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        $content = $request->request->all();
        $return = $this->get('postit.mongodb_client')->insert($content);

        if ($return) {
            return new JsonResponse($content, Response::HTTP_CREATED);
        }

        return new JsonResponse( ['message' => 'Unable to insert new record'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     */
    public function deleteAction(Request $request)
    {

    }

    /**
     * @param Request $request
     */
    public function editAction(Request $request)
    {

    }
}
