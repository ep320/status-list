<?php

namespace AppBundle\Controller;

use AppBundle\Form\EJPImportType;
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
        $ejpImportForm = $this->createForm(EJPImportType::class);
        $addPaperForm = $this->createForm(AddPaperType::class, $paper);
        $em = $this->getDoctrine()->getManager();

        $addPaperForm->handleRequest($request);
        $ejpImportForm->handleRequest($request);

        if ($addPaperForm->isSubmitted() && $addPaperForm->isValid()) {
            $em->persist($paper);
            $em->flush();
        }

        if ($ejpImportForm->isSubmitted() && $ejpImportForm->isValid()) {
            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            var_dump($ejpImportForm->get('ejpImport')->getData());

        }


        $papers = $em->getRepository(Paper::class)->findAll();


        return $this->render('papers/index.html.twig', [
            'papers' => $papers,
            'addPaperForm' => $addPaperForm->createView(),
            'ejpImportForm' => $ejpImportForm->createView(),
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

    /**
     * Shows details of one paper
     *
     * @Route("/papers/{manuscriptNo}", requirements={"manuscriptNo"="\d+"}, name="paperdetails")
     */
    public function showPaperAction($manuscriptNo)
    {
        $em = $this->getDoctrine()->getManager();
        $paper = $em->getRepository(Paper::class)->findOneBy(['manuscriptNo' => $manuscriptNo]);

        return $this->render('papers/paper.html.twig',
            ['paper'=> $paper]);
    }
}
