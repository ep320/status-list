<?php

namespace AppBundle\Controller;

use AppBundle\Form\AssignDigestWriterType;
use AppBundle\Form\AssignInsightEditorType;
use AppBundle\Form\DecideNoInsightType;
use AppBundle\Form\InsightAuthorAskedType;
use AppBundle\Form\InsightAuthorRefusedType;
use AppBundle\Form\InsightCommissionedType;
use AppBundle\Form\MarkAnswersReceivedType;
use AppBundle\Form\MarkDigestReceivedType;
use AppBundle\Form\MarkNoDigestDecidedType;
use AppBundle\Form\UndoAnswersReceivedType;
use AppBundle\Form\UndoNoDigestDecidedType;
use AppDomain\Command\AskInsightAuthor;
use AppDomain\Command\AssignDigestWriter;
use AppDomain\Command\AssignInsightEditor;
use AppDomain\Command\CommissionInsight;
use AppDomain\Command\DecideToNotCommissionInsight;
use AppDomain\Command\InsightAuthorRefuses;
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
        $noInsightForm = $this->buildAndHandleNoInsightForm($paper, $request, $validFormSubmitted);
        $insightAuthorAskedForm = $this->buildAndHandleInsightAuthorAskedForm($paper, $request, $validFormSubmitted);
        $insightAuthorRefusedForm = $this->buildAndHandleInsightAuthorRefusedForm($paper, $request, $validFormSubmitted);
        $insightCommissionedForm = $this->buildAndHandleInsightCommissionedForm($paper, $request, $validFormSubmitted);
        $insightAuthorRemindedForm = $this->buildAndHandleInsightAuthorRemindedForm($paper, $request, $validFormSubmitted);
        $insightAcknowledgedForm = $this->buildAndHandleInsightAcknowledgedForm($paper, $request, $validFormSubmitted);
        $assignInsightEditorForm = $this->buildAndHandleAssignInsightEditorForm($paper, $request, $validFormSubmitted);
        $insightAuthorCheckingForm = $this->buildAndHandleInsightAuthorCheckingForm($paper, $request, $validFormSubmitted);
        $signOffInsightForm = $this->buildAndHandleSignOffInsightForm($paper, $request, $validFormSubmitted);

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
            'signOffDigestForm' => $signOffDigestForm->createView(),
            'noInsightForm' => $noInsightForm->createView(),
            'insightAuthorAskedForm' => $insightAuthorAskedForm->createView(),
            'insightAuthorRefusedForm' => $insightAuthorRefusedForm->createView(),
            'insightCommissionedForm' => $insightCommissionedForm->createView(),
            'insightAuthorRemindedForm' => $insightAuthorRemindedForm->createView(),
            'insightAcknowledgedForm' => $insightAcknowledgedForm->createView(),
            'assignInsightEditorForm' => $assignInsightEditorForm->createView(),
            'insightAuthorCheckingForm' => $insightAuthorCheckingForm->createView(),
            'signOffInsightForm' => $signOffInsightForm->createView()
        ]);
    }

    private function buildAndHandleAnswersForm(Paper $paper, Request $request, &$validFormSubmitted)
    {

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

    private function buildAndHandleNoDigestForm(Paper $paper, Request $request, &$validFormSubmitted)
    {

        if ($paper->getNoDigestStatus()) { //No digest decision already submitted. Show/handle undo requests
            $form = $this->createForm(UndoNoDigestDecidedType::class, new UndoNoDigestDecided($paper->getId()));
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $this->getCommandHandler()->undoNoDigestDecided($form->getData());
                $validFormSubmitted = true;
            }
        } else { //No digest decision not submitted, show full no digest form
            $form = $this->createForm(MarkNoDigestDecidedType::class, new MarkNoDigestDecided($paper->getId()));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getCommandHandler()->markNoDigestDecided($form->getData());
                $validFormSubmitted = true;
            }
        }

        return $form;
    }

    private function buildAndHandleDigestActionForm(Paper $paper, Request $request, &$validFormSubmitted)
    {
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
                'em' => $this->getDoctrine()->getEntityManager()
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getCommandHandler()->assignDigestWriter($form->getData());
                $validFormSubmitted = true;
            }
        }

        return $form;
    }

    private function buildAndHandleNoInsightForm(Paper $paper, Request $request, &$validFormSubmitted)
    {


        $form = $this->createForm(DecideNoInsightType::class, new DecideToNotCommissionInsight($paper->getId()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getCommandHandler()->decideNotToCommissionInsight($form->getData());
            $validFormSubmitted = true;
        }

        return $form;
    }

    private function buildAndHandleInsightAuthorAskedForm(Paper $paper, Request $request, &$validFormSubmitted)
    {


        $form = $this->createForm(InsightAuthorAskedType::class, new AskInsightAuthor($paper->getId()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getCommandHandler()->askInsightAuthor($form->getData());
            $validFormSubmitted = true;
        }

        return $form;
    }

    private function buildAndHandleInsightAuthorRefusedForm(Paper $paper, Request $request, &$validFormSubmitted)
    {


        $form = $this->createForm(InsightAuthorRefusedType::class, new InsightAuthorRefuses($paper->getId()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getCommandHandler()->insightAuthorRefuses($form->getData());
            $validFormSubmitted = true;
        }

        return $form;
    }

    private function buildAndHandleInsightCommissionedForm(Paper $paper, Request $request, &$validFormSubmitted)
    {


        $form = $this->createForm(InsightCommissionedType::class, new CommissionInsight($paper->getId()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getCommandHandler()->commissionInsight($form->getData());
            $validFormSubmitted = true;
        }

        return $form;
    }

    private function buildAndHandleInsightAuthorRemindedForm(Paper $paper, Request $request, &$validFormSubmitted)
    {

        $builder = $this->createFormBuilder();
        $builder->add('AuthorReminded', SubmitType::class);
        $insightAuthorRemindedForm = $builder->getForm();
        $insightAuthorRemindedForm->handleRequest($request);
        if ($insightAuthorRemindedForm->isSubmitted()) {
            $this->getCommandHandler()->remindInsightAuthor($paper->getId());
            $validFormSubmitted = true;
        }

        return $insightAuthorRemindedForm;

    }

    private function buildAndHandleInsightAcknowledgedForm(Paper $paper, Request $request, &$validFormSubmitted)
    {

        $builder = $this->createFormBuilder();
        $builder->add('AuthorAcknowledged', SubmitType::class);
        $insightAcknowledgedForm = $builder->getForm();
        $insightAcknowledgedForm->handleRequest($request);
        if ($insightAcknowledgedForm->isSubmitted()) {
            $this->getCommandHandler()->acknowledgeInsight($paper->getId());
            $validFormSubmitted = true;
        }

        return $insightAcknowledgedForm;

    }

    private function buildAndHandleAssignInsightEditorForm(Paper $paper, Request $request, &$validFormSubmitted)
    {


        $form = $this->createForm(AssignInsightEditorType::class, new AssignInsightEditor($paper->getId()), [
            'em' => $this->getDoctrine()->getEntityManager()
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getCommandHandler()->assignInsightEditor($form->getData());
            $validFormSubmitted = true;
        }

        return $form;
    }

    private function buildAndHandleInsightAuthorCheckingForm(Paper $paper, Request $request, &$validFormSubmitted)
    {

        $builder = $this->createFormBuilder();
        $builder->add('AuthorAcknowledged', SubmitType::class);
        $insightAuthorCheckingForm = $builder->getForm();
        $insightAuthorCheckingForm->handleRequest($request);
        if ($insightAuthorCheckingForm->isSubmitted()) {
            $this->getCommandHandler()->insightAuthorChecking($paper->getId());
            $validFormSubmitted = true;
        }

        return $insightAuthorCheckingForm;

    }

    private function buildAndHandleSignOffInsightForm(Paper $paper, Request $request, &$validFormSubmitted)
    {

        $builder = $this->createFormBuilder();
        $builder->add('AuthorAcknowledged', SubmitType::class);
        $signOffInsightForm = $builder->getForm();
        $signOffInsightForm->handleRequest($request);
        if ($signOffInsightForm->isSubmitted()) {
            $this->getCommandHandler()->signOffInsight($paper->getId());
            $validFormSubmitted = true;
        }

        return $signOffInsightForm;

    }


}
