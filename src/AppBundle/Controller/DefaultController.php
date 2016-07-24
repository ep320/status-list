<?php

namespace AppBundle\Controller;

use AppDomain\CommandHandler;
use AppBundle\EJPImport\CSVParser;
use AppBundle\Form\EJPImportType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $addPaperForm = $this->createForm(AddPaperType::class, $addPaperCommand);
        $ejpImportForm = $this->createForm(EJPImportType::class);
        $em = $this->getDoctrine()->getManager();

        $addPaperForm->handleRequest($request);
        $ejpImportForm->handleRequest($request);

        if ($addPaperForm->isSubmitted() && $addPaperForm->isValid()) {
            $this->getCommandHandler()->addPaperManually($addPaperCommand);
            $em->flush();
        }

        if ($ejpImportForm->isSubmitted() && $ejpImportForm->isValid()) {
            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            $csvParser = new CSVParser($em);
            /**
             * @var $uploadedFile UploadedFile
             **/
            $uploadedFile = $ejpImportForm->get('ejpImport')->getData();
            $csvPapers = $csvParser->parseCSV($uploadedFile->openFile());
            /**
             * @var $paperFromCSV Paper
             */
            foreach ($csvPapers as $paperFromCSV) {
                if ($em->getRepository(Paper::class)->findOneBy(['manuscriptNo' => $paperFromCSV->getManuscriptNo()])) {
                } else {
                    $em->persist($paperFromCSV);
                }
            }
            $em->flush();
        }

        $papers = $em->getRepository(Paper::class)->findAll();


        return $this->render('papers/index.html.twig', [
            'papers' => $papers,
            'addPaperForm' => $addPaperForm->createView(),
            'ejpImportForm' => $ejpImportForm->createView(),
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
            ['paper' => $paper]);
    }
}
