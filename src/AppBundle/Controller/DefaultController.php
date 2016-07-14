<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\AddPaperType;
use AppBundle\Entity\AddPaper;
use AppBundle\Entity\Paper;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
        ]);
    }

    /**
     * Shows main list of papers
     *
     * @Route("/papers", name="papers")
     */
    public function showListAction(Request $request)
    {


        $paper = new Paper;
        $form = $this->createForm(AddPaperType::class, $paper);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($paper);
            $em->flush();

            // ... perform some action, such as saving the task to the database

            //return $this->redirectToRoute('task_success');
        }

        $papers = $em->getRepository(Paper::class)->findAll();


        return $this->render('papers/index.html.twig', [
            'papers' => $papers,
            'form' => $form->createView(),
            'addPaper' => $paper


        ]);
    }

    /**
     * Creates form for updating list of papers
     * @Route("/addpaper", name="addpaper")
     */
    public function AddPaper()
    {

        return $this->render('papers/index.html.twig', array());
    }
}
