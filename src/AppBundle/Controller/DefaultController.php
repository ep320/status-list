<?php

namespace AppBundle\Controller;

use AppDomain\Command\AddCommentCommand;
use AppDomain\CommandHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\AddPaperType;
use AppDomain\Command\AddPaperManually;
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


        $addPaperCommand = new AddPaperManually();
        $form = $this->createForm(AddPaperType::class, $addPaperCommand);
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getCommandHandler()->addPaperManually($addPaperCommand);
            $em->flush();

            // ... perform some action, such as saving the task to the database

            //return $this->redirectToRoute('task_success');
        }

        $papers = $em->getRepository(Paper::class)->findAll();


        return $this->render('papers/index.html.twig', [
            'papers' => $papers,
            'form' => $form->createView(),
            'addPaper' => $addPaperCommand


        ]);
    }

    /**
     * @return CommandHandler;
     */
    private function getCommandHandler()
    {
        return $this->get('command_handler');
    }
}
