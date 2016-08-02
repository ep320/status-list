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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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


        $answersForm;
        if ($paper->getAnswersStatus()) { //Answers already submitted. Show/handle undo requests
            $builder = $this->createFormBuilder();
            $builder->add('Undo', SubmitType::class);
            $answersForm = $builder->getForm();
            $answersForm->handleRequest($request);
            if ($answersForm->isSubmitted()) {
                $this->getCommandHandler()->undoAnswersReceived($paper->getId());
                $em->flush();

               return $this->redirectToRoute('paperdetails', ['manuscriptNo'=>$manuscriptNo]);
            }
        } else { //Answers not submitted, show full answers form
            $markAnswersReceivedCommand = new MarkAnswersReceived();
            $markAnswersReceivedCommand->paperId = $paper->getId();
            $answersForm = $this->createForm(MarkAnswersReceivedType::class, $markAnswersReceivedCommand);
            $answersForm->handleRequest($request);
            if ($answersForm->isSubmitted() && $answersForm->isValid()) {
                $this->getCommandHandler()->markAnswersReceived($markAnswersReceivedCommand);
                $em->flush();

               return $this->redirectToRoute('paperdetails', ['manuscriptNo'=>$manuscriptNo]);
            }
        }

        return $this->render('papers/paper.html.twig', [
            'paper' => $paper,
            'answersForm' => $answersForm->createView()
        ]);
    }


}
