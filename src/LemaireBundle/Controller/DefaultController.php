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
       
        $cars = $em->getRepository('LemaireBundle:Car')->findBy(array('active' => true), array('date' => 'DESC' ));
        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
        
        $photos=[];
        foreach ($cars as $car){
             $photo = $em->getRepository('LemaireBundle:Image')->findBy(array('car' => $car->getId()));
                   
             if (isset($photo[0])){
             array_push($photos, $photo[0]);

             
             $optionArray = explode(", ",$car->getOptions());
             $car->setOptions($optionArray);

             }
//        echo '<pre>';
////        var_dump($cars);
//        var_dump($photos);
//        echo '</pre>';
            
//        $photos[$car->getId()] = $photo;
        }
        

 

        
        return $this->render('@Lemaire/Default/index.html.twig', array(
            'cars' => $cars,
            'photos' => $photos,
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
