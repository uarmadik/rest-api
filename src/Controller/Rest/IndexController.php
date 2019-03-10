<?php

namespace App\Controller\Rest;

use App\Entity\Task;
use App\Repository\TaskRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class IndexController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/tasks")
     */
    public function tasks()
    {
        $respository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $respository->findAll();
        $result = [
            'rows' => $tasks,
        ];
        $view = new View($result, Response::HTTP_OK);
        $view->setHeader('Access-Control-Allow-Origin', '*');
        return $view;
    }

    /**
     * @Rest\Get("/task/{id}")
     */
    public function task($id)
    {
        $respository = $this->getDoctrine()->getRepository(Task::class);
        $task = $respository->find($id);
        return View::create($task, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/task")
     */
    public function saveTask(Request $request):View
    {
        $title = $request->get('title');
        $content = $request->get('content');
        if(empty($title) || empty($content)) {
            return new View('', Response::HTTP_BAD_REQUEST);
        };

        $task = new Task();
        $task->setTitle($title);
        $task->setContent($content);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();

        return new View($task, Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @param Request $request
     * @return View
     * @Rest\Put("/task/{id}")
     */
    public function editTask($id, Request $request):View
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $task = $repository->findOneBy(['id' => $id]);

        if (isset($task)) {
            $task->setTitle($request->get('title'));
            $task->setContent($request->get('content'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return View::create($task, Response::HTTP_OK);
        }
        return View::create(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $id
     * @Rest\Delete("/task/{id}")
     * @return View
     */
    public function delete($id):View
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $task = $repository->findOneBy(['id' => $id]);
        if ($task === null) {
            return View::create(null, Response::HTTP_NOT_FOUND);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        return View::create([], Response::HTTP_OK);
    }
}