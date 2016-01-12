<?php

namespace PostIt\Controller;

use PostIt\Form\Type\PostItType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PostItController extends Controller
{
    /**
     * @ApiDoc(
     *     description="Return list of Post-Its"
     * )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $content = iterator_to_array($this->get('postit.mongodb_client')->select());

        return new JsonResponse($content);
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     description="Return one Post-It",
     *     requirements={
     *         {"name"="id", "dataType"="string","required"="true","description"="PostIt Id"}
     *     }
     * )
     *
     * @param $id
     * @return JsonResponse
     */
    public function showAction($id)
    {
        try {
            $content = $this->get('postit.mongodb_client')->show($id);
            return new JsonResponse($content, Response::HTTP_OK);

        } catch (\MongoException $e) {
            return new JsonResponse( ['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @ApiDoc(
     *     description="Create a new Post-It",
     *     input="PostIt\Form\Type\PostItType"
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm( PostItType::class );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $content = $form->getData();
            $return = $this->get('postit.mongodb_client')->insert($content);

            if ($return) {
                return new JsonResponse($content, Response::HTTP_CREATED);
            }
        }

        return new JsonResponse( ['message' => 'Unable to insert new record'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     description="Remove a Post-It",
     *     requirements={
     *         {"name"="id", "dataType"="string","required"="true","description"="PostIt id"}
     *     }
     * )
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        try {
            $content = $this->get('postit.mongodb_client')->show($id);
            $return = $this->get('postit.mongodb_client')->delete($id);

            if ($return['n'] > 0) {
                return new JsonResponse($content, Response::HTTP_OK);
            }

            return new JsonResponse($content, Response::HTTP_NOT_MODIFIED);

        } catch (\MongoException $e) {
            return new JsonResponse( ['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     description="Update a Post-It",
     *     input="PostIt\Form\Type\PostItType"
     * )
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function editAction($id, Request $request)
    {
        try {
            $content = $this->get('postit.mongodb_client')->show($id);
            $form = $this->createForm( PostItType::class, $content, [
                'method' => $request->getMethod()
            ] );
            $form->handleRequest($request);
            $content = $form->getData();
            if (!$form->isValid()) {
                return new JsonResponse($content, Response::HTTP_NOT_MODIFIED);
            }

            $return = $this->get('postit.mongodb_client')->update($id, $content);
            if ($return) {
                return new JsonResponse($content, Response::HTTP_OK);
            }

        } catch (\MongoException $e) {
            return new JsonResponse( ['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
