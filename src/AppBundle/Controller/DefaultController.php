<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        
        $this->convertCarAction($request);
        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig');
            
        
    }
    
    

    public function convertCarAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();

        // Saisie des Modèles et marques (avoir les marques)
        
//        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
//        
//        $carsArray = [];
//        foreach ($marques as $key => $marque){
//            
//            $CARS_BY_MARQUES_QUERY = 'SELECT designation FROM lemaire_autos_vehicules WHERE marque = "'.$marque->getName().'";';    
//            $carsbymarques_statement = $em->getConnection()->prepare($CARS_BY_MARQUES_QUERY);
//            $carsbymarques_statement->execute();
//            $carsbymarques_results = $carsbymarques_statement->fetchAll();
//            
//            $regex_designation = '/^([A-Za-z0-9\s+-]*)\s([0-9].[0-9]\s?L?[\sa-zA-Z0-9]*),\s?([0-9]*)cv/si';
//                       
//            $carsArray[$key]['marque'] = $marque->getId();
//            
//            $modeleArray = [];
//            
//            foreach ($carsbymarques_results as $carbymarque){
//                preg_match_all($regex_designation, $carbymarque["designation"], $match_designation, PREG_SET_ORDER, 0);
//                
//                if (!(in_array($match_designation[0][1], $modeleArray))){
//
//                    array_push($modeleArray, $match_designation[0][1]);
//                    
//                    $modele = new \LemaireBundle\Entity\Modele;
//                    $modele->setMarque($marque);
//                    $modele->setName($match_designation[0][1]);
//                    $em->persist($modele);
//                    $em->flush();
//                }
//            }
//            
//            $carsArray[$key]['Modeles'] = $modeleArray;    
//        }

        $OPTIONS_QUERY = 'SELECT options FROM lemaire_autos_vehicules;';    
        $options_statement = $em->getConnection()->prepare($OPTIONS_QUERY);
        $options_statement->execute();
        $options_results = $options_statement->fetchAll();
        
        $regex_options = '/([A-Za-z0-9éèàùê&\)\(\s-])*/si';
        
        $OptionsArray = [];
         foreach ($options_results as $options){
            
          
            preg_match_all($regex_options, $options["options"], $match_options, PREG_SET_ORDER, 0);
        
            foreach ($match_options as $option){
//                 echo "<pre>";
//                var_dump($option[0]);
//                echo "</pre>";
                $regex_space = '/^\s*/s';
                preg_replace($regex_space,'', $option[0]);
                
                 if ($option[0]!=="" && !(in_array($option[0], $OptionsArray))){
                array_push($OptionsArray, $option[0]);
            }
                
            }
               

           
         }
         
                echo "<pre>";
                var_dump($OptionsArray);
                echo "</pre>";
        
        $CARS_QUERY = 'SELECT * FROM lemaire_autos_vehicules;';    
        $cars_statement = $em->getConnection()->prepare($CARS_QUERY);
        $cars_statement->execute();
        $cars_results = $cars_statement->fetchAll();
        
            
          $regex_designation = '/^([A-Za-z0-9\s+-]*)\s([0-9].[0-9]\s?L?[\sa-zA-Z0-9]*),\s?([0-9]*)cv/si';
            
        foreach ($cars_results as $result){
            
          
            preg_match_all($regex_designation, $result["designation"], $match_designation, PREG_SET_ORDER, 0);
            
//                                    echo "<pre>";
//            var_dump($match_designation);
//            var_dump($result['id']);
//            
//            echo "</pre>";
            
            $modele = $match_designation[0][1];
            $motorisation = $match_designation[0][2];
            $cvFiscaux = $match_designation[0][3];


            
            $description = $result['descr'];
            $annee = $result['annee'];
            $kms = $result['km'];
            $prixDestock = $result['prix'];
            $vendu = $result['vendu'];
            $id = $result['id'];
            
            // Conversion marque :
            $marque = $result['marque'];
            
             
             
             
            
            
            
        }
        
        die;
        
        
    }
    
}
