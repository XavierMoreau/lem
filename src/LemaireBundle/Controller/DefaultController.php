<?php

namespace LemaireBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
       
        $cars = $em->getRepository('LemaireBundle:Car')->findBy(array('active' => true));
        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
 

        
        return $this->render('@Lemaire/Default/index.html.twig', array(
            'cars' => $cars,
            'marques' => $marques,
            'energies' => $energies,
        ));
    }
    
     /**
     * @Route("/infos", name="infos")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function infosAction()
    {   
        
        return $this->render('@Lemaire/Default/infos.html.twig', array(  
        ));
    }
      
    
}
