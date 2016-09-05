<?php

namespace AppBundle\Controller;

use AppBundle\Form\AssignDigestWriterType;
use AppBundle\Form\MarkAnswersReceivedType;
use AppBundle\Form\MarkDigestReceivedType;
use AppBundle\Form\MarkNoDigestDecidedType;
use AppBundle\Form\UndoAnswersReceivedType;
use AppBundle\Form\UndoNoDigestDecidedType;
use AppDomain\Command\AssignDigestWriter;
use AppDomain\Command\MarkAnswersReceived;
use AppDomain\Command\MarkDigestReceived;
use AppDomain\Command\MarkNoDigestDecided;
use AppDomain\Command\UndoAnswersReceived;
use AppDomain\Command\UndoNoDigestDecided;
use AppDomain\CommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Paper;
use Symfony\Component\HttpFoundation\Response;

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

        if (!$paper) {
            return new Response('Paper not found', 404);
        }

        $validFormSubmitted = false;
        $answersForm = $this->buildAndHandleAnswersForm($paper, $request, $validFormSubmitted);
        $noDigestForm = $this->buildAndHandleNoDigestForm($paper, $request, $validFormSubmitted);
        $digestWriterForm = $this->buildAndHandleDigestActionForm($paper, $request, $validFormSubmitted);

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
            'digestWriterForm' => $digestWriterForm->createView(),
            'signOffDigestForm' => $signOffDigestForm->createView()
        ]);
    }

    private function buildAndHandleAnswersForm(Paper $paper, Request $request, &$validFormSubmitted) {

        if ($paper->getAnswersStatus()) { //Answers already submitted. Show/handle undo requests
            $form = $this->createForm(UndoAnswersReceivedType::class, new UndoAnswersReceived($paper->getId()));
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $this->getCommandHandler()->undoAnswersReceived($form->getData());
                $validFormSubmitted = true;
            }
        } else { //Answers not submitted, show full answers form
            $form = $this->createForm(MarkAnswersReceivedType::class, new MarkAnswersReceived($paper->getId()));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getCommandHandler()->markAnswersReceived($form->getData());
                $validFormSubmitted = true;
            }
        }

        return $form;
    }

    private function buildAndHandleNoDigestForm(Paper $paper, Request $request, &$validFormSubmitted) {

        if ($paper->getNoDigestStatus()) { //No digest decision already submitted. Show/handle undo requests
            $form = $this->createForm(UndoNoDigestDecidedType::class, new UndoNoDigestDecided($paper->getId()));
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $this->getCommandHandler()->undoNoDigestDecided($form->getData());
                $validFormSubmitted = true;
            }
        } else { //No digest decision not submitted, show full no digest form
            $form = $this->createForm(MarkNoDigestDecidedType::class,new MarkNoDigestDecided($paper->getId()));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getCommandHandler()->markNoDigestDecided($form->getData());
                $validFormSubmitted = true;
            }
        }

        return $form;
    }

    private function buildAndHandleDigestActionForm(Paper $paper, Request $request, &$validFormSubmitted) {
        //TODO: need to add functionality to skip to editing form if writer = features team
        if ($paper->getDigestWrittenBy()) { //Digest has been assigned to a writer. Show button to mark digest received
            $form = $this->createForm(MarkDigestReceivedType::class, new MarkDigestReceived($paper->getId()));
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $this->getCommandHandler()->markDigestReceived($form->getData());
                $validFormSubmitted = true;
            }
        } else { //Digest not yet assigned to writer
            $form = $this->createForm(AssignDigestWriterType::class, new AssignDigestWriter($paper->getId()), [
                'em'=>$this->getDoctrine()->getEntityManager()
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getCommandHandler()->assignDigestWriter($form->getData());
                $validFormSubmitted = true;
            }
        }

        return $form;
    }
}
