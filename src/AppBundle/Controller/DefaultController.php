<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('papers/index.html.twig', [
            'papers' => [[
                'manNo' => 12342,
                'corrAuth' => 'Smith',
                'paperType' => 'TR',
                'subArea1' => 'BIOCHEM',
                'subArea2' => 'BIOPHYS'
            ], [
                'manNo' => 17945,
                'corrAuth' => 'Jones',
                'paperType' => 'RA',
                'subArea1' => 'NEURO',
                'subArea2' => 'CELL'
            ], [
                'manNo' => 16111,
                'corrAuth' => 'McDougal',
                'paperType' => 'SR',
                'subArea1' => 'PLANT',
                'subArea2' => 'EPI'
            ]]
        ]);
    }
}
