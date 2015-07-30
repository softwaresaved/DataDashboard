<?php

namespace AppBundle\Controller;

use AppBundle\Model\ParseData;
use AppBundle\Form\Type\CreateQueryType;
use AppBundle\Form\Type\ParseQueryType;
use AppBundle\Form\Type\GetQueryType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use SSI\DataBundle\Entity\format;
use SSI\DataBundle\Entity\runQuery;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;



class DefaultController extends Controller
{

    /**
    * Function to show the create form page
    * @Route("/data/create")
    */
    public function createAction (Request $request)
    {
        $options = Array();
        $form = $this->createForm(new CreateQueryType(), $options);

        //handle request loop
        $form->handleRequest($request);

        if ($form->isValid()) {
             $p = new ParseQueryType();
             $p->ParseQuery($form->getData());

             return $this->redirect('../');
        }

        return $this->render('default/createquery.html.twig', array(
            'form' => $form->createView())
        );
    }

    /**
    *
    * Function to provide the graph template
    * 
    * @Route("/{hash}")
    */
    public function getData($hash)
    {
        $get = new GetQueryType();

        $return_hash = $get->getHashQuery($hash);
        if ($return_hash['query'] == 'none') {
               return $this->redirect('../');   
        } else {
           $data = $get->runQuery($return_hash['query']);
           if ($return_hash['vis'] == 'none' || !$return_hash['vis']) {
              throw $this->createNotFoundException('The identity does not exist');
           } else {
              $f = new format();
              $run = new runQuery();

              if ($return_hash['vis'] == 'pie') {
                  return $this->render('default/pie.html.twig',
                                   array('data' => $f->formatDataPie($data,
                                             $return_hash['datastruc']), 
                                             'hash' => $hash, 
                                             'desc' => $return_hash['description'],
                                             'title' => $return_hash['title'])
                                      );
              } else {
                  return $this->render('default/'.$return_hash['vis'].'.html.twig',
                                           array('data' => $f->formatDataHist($data, 
                                               $return_hash['datastruc']), 
                                               'hash' => $hash,
                                               'desc' => $return_hash['description'],
                                               'title' => $return_hash['title'])
                                      );
              }
           }
        }     
    }

    /**
    *
    * Function to provide the data behind the graph
    *
    * @Route("/{hash}/{type}")
    */
    public function getDataType($hash, $type)
    {
        $get = new GetQueryType();

        $return_hash = $get->getHashQuery($hash);
        if ($return_hash['query'] == 'none') {
               // this hash cannot be matched so redirect to existing list
               return $this->redirect('/');
        } else {
           $data = $get->runQuery($return_hash['query']);
           $f = new format();
           if ($type == 'json') {
              if ($return_hash['vis'] == 'pie') {
                  return new JsonResponse(array('data' => $f->formatDataPie($data,$return_hash['datastruc'])));
              } else {
                  return new JsonResponse(array('data' => $f->formatDataHist($data,$return_hash['datastruc'])));
              }
           } else if ($type == 'csv') {
              // return this as a Streamed Response
              return $this->render('default/csv.html.twig',array('data' => implode(',', $f->formatDataPie($data,$return_hash['datastruc']))));
           } else {
              return $this->render('/');
           }
        }
    }
    
    /**
    * @Route("/")
    */
    public function saveData () 
    {
        $get = new GetQueryType();
        $queries = $get->fetchTables();
        return $this->render('default/dblist.html.twig', 
                                array ('queries' => $queries, 
                                   'title' => 'Software Sustainability Institute'
                                )
                            );
    }
}
