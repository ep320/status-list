<?php

namespace AppBundle\Controller;

use AppDomain\Command\ImportPaperDetails;
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

class PaperListController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute("papers");
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
            $importCommands = $csvParser->parseCSV($uploadedFile->openFile());
            /**
             * @var $importCommand ImportPaperDetails
             */
            foreach ($importCommands as $importCommand) {
                $this->getCommandHandler()->importPaperDetails($importCommand);
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
     * @Route("/papers/digesttodolist", name="digestlist")
     */
    public function DigestListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $papers = $em->getRepository(Paper::class)->findAll();

        return $this->render('papers/maindigestpage.html.twig', [
            'papers' => $papers
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
