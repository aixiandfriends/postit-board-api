<?php

namespace PostIt\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
            return new JsonResponse($content);

        } catch (\MongoException $e) {
            return new JsonResponse( ['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     description="Create a new Post-It",
     *     requirements={
     *         {"name"="message", "dataType"="string","required"="true","description"="Content of new PostIt"}
     *     }
     * )
     *
     * @param Request $request
     * @return JsonResponse
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
            $this->get('postit.mongodb_client')->delete($id);
            return new JsonResponse($content, Response::HTTP_OK);
        } catch (\MongoException $e) {
            return new JsonResponse( ['message' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @ApiDoc(
     *     resource=true,
     *     description="Update a Post-It",
     *     requirements={
     *         {"name"="id", "dataType"="string","required"="true","description"="PostIt id"}
     *     }
     * )
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function editAction($id, Request $request)
    {
        try {
            $content = $request->request->all();
            $return = $this->get('postit.mongodb_client')->update($id, $content);

            if ($return) {
                return new JsonResponse($content, Response::HTTP_CREATED);
            }
        } catch (\MongoException $e) {
            return new JsonResponse( ['message' => $e->getMessage()], $e->getCode());
        }
    }
}
