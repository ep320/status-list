<?php

namespace AppBundle\Controller;

use AppBundle\Form\MarkAnswersReceivedType;
use AppDomain\Command\ImportPaperDetails;
use AppDomain\Command\MarkAnswersReceived;
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

class PaperController extends Controller
{
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
    public function showPaperAction(Request $request, $manuscriptNo)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var $paper Paper
         */
        $paper = $em->getRepository(Paper::class)->findOneBy(['manuscriptNo' => $manuscriptNo]);

        $markAnswersRecievedCommand = new MarkAnswersReceived();
        $markAnswersRecievedCommand->paperId = $paper->getId();
        $answersReceivedForm = $this->createForm(MarkAnswersReceivedType::class, $markAnswersRecievedCommand);

        $answersReceivedForm->handleRequest($request);


        if ($answersReceivedForm->isSubmitted() && $answersReceivedForm->isValid()) {
            $this->getCommandHandler()->markAnswersReceived($markAnswersRecievedCommand);
            $em->flush();
        }
        return $this->render('papers/paper.html.twig', [
            'paper' => $paper,
            'answersReceivedForm' => $answersReceivedForm->createView()
        ]);
    }


}
