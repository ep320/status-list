<?php

namespace AppBundle\Controller;

use AppBundle\EJPImport\XLSParser;
use AppDomain\CommandHandler;
use AppBundle\EJPImport\CSVParser;
use AppBundle\Form\EJPImportType;
use AppDomain\Ejp\EjpPaper;
use AppDomain\Event\PaperAdded;
use AppDomain\Event\PaperEvent;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
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
        $addPaperForm = $this->createForm(AddPaperType::class, $addPaperCommand, ['em' => $this->getDoctrine()->getEntityManager()]);
        $ejpImportForm = $this->createForm(EJPImportType::class);
        $em = $this->getDoctrine()->getManager();

        $addPaperForm->handleRequest($request);


        if ($addPaperForm->isSubmitted() && $addPaperForm->isValid()) {
            $this->getCommandHandler()->addPaperManually($addPaperCommand);
            $em->flush();

            return $this->redirectToRoute('papers/index.html.twig');
        }

        $this->handleEJPSubmission($ejpImportForm, $em, $request);


        //Flush again to flush any changes picked up by PaperEvent postPersist
        $em->flush();

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
     * @Route("/papers/outforrevisioninsightslist", name="outforrevisioninsightslist")
     */
    public function papersOutForRevisionInsightDecisionsListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $papers = $em->getRepository(Paper::class)->findBy(['insightDecision' => ['no', 'yes']], ['insightUpdatedDate' => 'DESC']);
        $ejpImportForm = $this->createForm(EJPImportType::class);
        if ($this->handleEJPSubmission($ejpImportForm, $em, $request)){
            return $this->redirectToRoute('outforrevisioninsightslist');
        }

        return $this->render('papers/outforrevisioninsightslist.html.twig', [
            'papers' => $papers,
            'ejpImportForm' => $ejpImportForm->createView()
        ]);
    }

    /**
     * @Route("papers/insightsforstatuslist", name="insightsforstatuslist")
     */
    public function insightsForStatusListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $papers = $em->getRepository(Paper::class)->findBy(['accepted' => true, 'insightDecision' => ['Yes', 'Not Asked']], ['acceptedDate' => 'DESC']);
        $ejpImportForm = $this->createForm(EJPImportType::class);
        if ($this->handleEJPSubmission($ejpImportForm, $em, $request)){
            return $this->redirectToRoute('insightsforstatuslist');
        }

        return $this->render('papers/insightsforstatuslist.html.twig', [
            'papers' => $papers,
            'ejpImportForm' => $ejpImportForm->createView()
        ]);
    }


    /**
     * @Route("/papers/acceptedpaperslist", name="acceptedpaperslist")
     */
    public function AcceptedPapersListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $papers = $em->getRepository(Paper::class)->findBy(['accepted' => True], ['acceptedDate' => 'DESC']);
        $ejpImportForm = $this->createForm(EJPImportType::class);

        if ($this->handleEJPSubmission($ejpImportForm, $em, $request)) {
            return $this->redirectToRoute('acceptedpaperslist');
        }
        return $this->render('papers/acceptedpaperslist.html.twig', [
            'papers' => $papers,
            'ejpImportForm' => $ejpImportForm->createView()
        ]);

    }


    /**
     * @return CommandHandler;
     */
    private function getCommandHandler()
    {
        return $this->get('command_handler');
    }

    private function handleEJPSubmission(FormInterface $ejpImportForm, EntityManager $em, Request $request)
    {
        $ejpImportForm->handleRequest($request);
        if ($ejpImportForm->isSubmitted() && $ejpImportForm->isValid()) {
            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            /**
             * @var $csvParser XLSParser
             */
            $csvParser = $this->get('ejp_parser');

            /**
             * @var $uploadedFile UploadedFile
             **/
            $uploadedFile = $ejpImportForm->get('ejpImport')->getData();
            $ejpPapers = $csvParser->parse($uploadedFile->getRealPath());
            /**
             * @var $ejpPaper EjpPaper
             */
            foreach ($ejpPapers as $ejpPaper) {
                $this->getCommandHandler()->importFromEjp($ejpPaper);
            }
            $em->flush();
            return true;
        }
        return false;
    }
}
