<?php

namespace AppBundle\Controller;

use AppBundle\Form\AssignDigestWriterType;
use AppBundle\Form\MarkAnswersReceivedType;
use AppBundle\Form\MarkNoDigestDecidedType;
use AppDomain\Command\AssignDigestWriter;
use AppDomain\Command\MarkAnswersReceived;
use AppDomain\Command\MarkNoDigestDecided;
use AppDomain\CommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
     * @Method({"GET"})
     */
    public function showPaperAction(Request $request, $manuscriptNo)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var $paper Paper
         */
        $paper = $em->getRepository(Paper::class)->findOneBy(['manuscriptNo' => $manuscriptNo]);

        $validFormSubmitted = false;


        $answersForm = $this->buildAnswersForm();
        if ($paper->getAnswersStatus()) { //Answers already submitted. Show/handle undo requests
            $builder = $this->createFormBuilder();
            $builder->add('Undo answers', SubmitType::class);
            $answersForm = $builder->getForm();
            $answersForm->handleRequest($request);
            if ($answersForm->isSubmitted()) {
                $this->getCommandHandler()->undoAnswersReceived($paper->getId());
                $validFormSubmitted = true;
            }
        } else { //Answers not submitted, show full answers form
            $markAnswersReceivedCommand = new MarkAnswersReceived();
            $markAnswersReceivedCommand->paperId = $paper->getId();
            $answersForm = $this->createForm(MarkAnswersReceivedType::class, $markAnswersReceivedCommand);
            $answersForm->handleRequest($request);
            if ($answersForm->isSubmitted() && $answersForm->isValid()) {
                $this->getCommandHandler()->markAnswersReceived($markAnswersReceivedCommand);
                $validFormSubmitted = true;
            }
        }

        $noDigestForm;
        if ($paper->getNoDigestStatus()) { //No digest decision already submitted. Show/handle undo requests
            $builder = $this->createFormBuilder();
            $builder->add('Undo no digest decision', SubmitType::class);
            $noDigestForm = $builder->getForm();
            $noDigestForm->handleRequest($request);
            if ($noDigestForm->isSubmitted()) {
                $this->getCommandHandler()->undoNoDigestDecided($paper->getId());
                $validFormSubmitted = true;
            }
        } else { //No digest decision not submitted, show full no digest form
            $markNoDigestDecidedCommand = new MarkNoDigestDecided();
            $markNoDigestDecidedCommand->paperId = $paper->getId();
            $noDigestForm = $this->createForm(MarkNoDigestDecidedType::class, $markNoDigestDecidedCommand);
            $noDigestForm->handleRequest($request);
            if ($noDigestForm->isSubmitted() && $noDigestForm->isValid()) {
                $this->getCommandHandler()->markNoDigestDecided($markNoDigestDecidedCommand);
                $validFormSubmitted = true;
            }
        }



        //need to add functionality to skip to editing form if writer = features team
        $assignDigestWriterForm;
        if ($paper->getDigestWrittenBy()) { //Digest has been assigned to a writer. Show button to mark digest received
            $builder = $this->createFormBuilder();
            $builder->add('digest received', SubmitType::class);
            $assignDigestWriterForm = $builder->getForm();
            $assignDigestWriterForm->handleRequest($request);
            if ($assignDigestWriterForm->isSubmitted()) {
                $this->getCommandHandler()->markDigestReceived($paper->getId());
                $validFormSubmitted = true;
            }
        } else { //Digest not yet assigned to writer

            $assignWriterCommand = new AssignDigestWriter();
            $assignWriterCommand->paperId = $paper->getId();
            $assignDigestWriterForm = $this->createForm(AssignDigestWriterType::class, $assignWriterCommand);
            $assignDigestWriterForm->handleRequest($request);
            if ($assignDigestWriterForm->isSubmitted() && $assignDigestWriterForm->isValid()) {
                $this->getCommandHandler()->assignDigestWriter($assignWriterCommand);
                $validFormSubmitted = true;
            }
        }

        //Sign off digest
        $builder = $this->createFormBuilder();
        $builder->add('sign off digest', SubmitType::class);
        $signOffDigestForm = $builder->getForm();
        $signOffDigestForm->handleRequest($request);
        if ($signOffDigestForm->isSubmitted()) {
            $this->getCommandHandler()->markDigestSignedOff($paper->getId());
            $validFormSubmitted = true;
        }

        //If any form has been submitted and is valid, save changes to database and redirect back here.
        if ($validFormSubmitted) {
            $em->flush();
            return $this->redirectToRoute('paperdetails', ['manuscriptNo' => $manuscriptNo]);
        }


        return $this->render('papers/paper.html.twig', [
            'paper' => $paper,
            'noDigestForm' => $noDigestForm->createView(),
            'answersForm' => $answersForm->createView(),
            'digestWriterForm' => $assignDigestWriterForm->createView(),
            'signOffDigestForm' => $signOffDigestForm->createView()
        ]);


    }
    /**
     * Shows details of one paper
     *
     * @Route("/papers/{manuscriptNo}", requirements={"manuscriptNo"="\d+"}, name="paperdetails")
     * @Method({"POST"})
     */
    public function postPaperAction(Request $request, $manuscriptNo)
    {
        $answersForm=$this->buildAnswersForm();
        $answersForm->handleRequest($request);
        if ($answersForm->isSubmitted()) {
            $this->getCommandHandler()->undoAnswersReceived($paper->getId());
            $em->flush();

            return $this->redirectToRoute('paperdetails', ['manuscriptNo' => $manuscriptNo]);
        }
    }

    private function buildAnswersForm(){
        $builder = $this->createFormBuilder();
        $builder->add('Undo answers', SubmitType::class);
        return $builder->getForm();
    }

}
