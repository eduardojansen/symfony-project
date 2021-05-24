<?php


namespace App\Controller;


use App\Actions\ImportXmlAction;
use App\Actions\ListXmlAction;
use App\Actions\UploadXmlAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ImportXMLController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(ListXmlAction $action)
    {
        return $this->render('import/index.html.twig', ['xmls' => ($action)()]);
    }

    /**
     * @Route("/upload", name="upload", methods="POST")
     * @param Request $request
     * @param UploadXmlAction $action
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function upload(Request $request, UploadXmlAction $action)
    {
        [$type, $message] = ($action)($request);
        $this->addFlash($type, $message);
        return $this->redirectToRoute('index');

    }

    /**
     * @Route("/import/{filename}", name="import", methods="GET")
     * @param $filename
     * @param ImportXmlAction $action
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function import($filename, ImportXmlAction $action)
    {
        [$type, $message] = ($action)($filename);
        $this->addFlash($type, $message);
        return $this->redirectToRoute('index');
    }
}