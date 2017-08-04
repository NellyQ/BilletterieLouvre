<?php
// src/Louvre/BilletterieBundle/Controller/BookingController.php

namespace Louvre\BilletterieBundle\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Louvre\BilletterieBundle\Entity\Commande;
use Louvre\BilletterieBundle\Form\CommandeType;
use Louvre\BilletterieBundle\Entity\Detail;
use Louvre\BilletterieBundle\Form\DetailType;
use Louvre\BilletterieBundle\Form\GlobalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends Controller
{
    public function commandeAction(Request $request)
        
    {   
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new DateTimeNormalizer('d-m-Y'));

        $serializer = new Serializer($normalizers, $encoders);
        
        //Récupération du nombre total de billet déjà par jour avec la fonction totalNbBilletJour() définit dans CommandeRepository.php
        $arrayTotalBillets = $this->getDoctrine()
                                    ->getManager()
                                    ->getRepository('LouvreBilletterieBundle:Commande')
                                    ->totalNbBilletJour();
        //On sérialise les données
        $jsonTotalBillets = $serializer->serialize($arrayTotalBillets, 'json');
        
        $session = $request->getSession();
        $commande = new Commande();
        $form = $this->get('form.factory')->create(CommandeType::class, $commande);
        
        
        //Enregistrement des données dans la bdd si le formulaire est valide et renvoi vers la page details
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $session->set('commande', $commande);

      return $this->redirectToRoute('louvre_billetterie_details');
    }

    return $this->render('LouvreBilletterieBundle:Booking:commande.html.twig', array(
        'form' => $form->createView(),
        'arrayTotalBillets'=> $arrayTotalBillets,
        'jsonTotalBillets' => $jsonTotalBillets,
    ));
  }
    
    public function detailsAction(Request $request)
    {
        $session = $request->getSession();
        $commande = $session->get('commande');
        $commandeId = $commande->getCommandeId();
        $commandeNbBillet = $commande->getCommandeNbBillet();
        
        $detail = new Detail();
        $detail->setCommandeId($commandeId);
        
        $form = $this->get('form.factory')->create(DetailType::class, $detail);
        

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($detail);
        $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'renseignements bien enregistrée.');

      return $this->redirectToRoute('louvre_billetterie_payment');
        }

    return $this->render('LouvreBilletterieBundle:Booking:details.html.twig', array(
        'form' => $form->createView(),
        'commande'=> $commande,
    ));
    }
    
    public function paymentAction()
    {
       
      return $this->render('LouvreBilletterieBundle:Booking:payment.html.twig');
    }

}
