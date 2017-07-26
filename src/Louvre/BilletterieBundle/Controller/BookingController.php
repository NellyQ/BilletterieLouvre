<?php
// src/Louvre/BilletterieBundle/Controller/BookingController.php

namespace Louvre\BilletterieBundle\Controller;

use Louvre\BilletterieBundle\Entity\Commande;
use Louvre\BilletterieBundle\Form\CommandeType;
use Louvre\BilletterieBundle\Entity\Details;
use Louvre\BilletterieBundle\Form\DetailsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends Controller
{
    public function commandeAction(Request $request)
        
    {
        $session = $request->getSession();
        $commande = new Commande();
        $form = $this->get('form.factory')->create(CommandeType::class, $commande);
        

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $session->set('commande_id', $commande->getCommandeId());
        $session->set('commande_nbBillet', $commande->getCommandeNbBillet());
            

      $request->getSession()->getFlashBag()->add('notice', 'Commande bien enregistrée.');

      return $this->redirectToRoute('louvre_billetterie_details');
    }

    return $this->render('LouvreBilletterieBundle:Booking:commande.html.twig', array(
      'form' => $form->createView(),
    ));
  }
    
    public function detailsAction(Request $request)
    {
        $session = $request->getSession();
        $commandeId = $session->get('commande_id');
        $commandeNbBillet = $session->get('commande_nbBillet');
        
        $details = new Details();
        $details->setCommandeId($commandeId);
        
        $form = $this->get('form.factory')->create(DetailsType::class, $details);
        

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($details);
        $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'renseignements bien enregistrée.');

      return $this->redirectToRoute('louvre_billetterie_payment');
        }

    return $this->render('LouvreBilletterieBundle:Booking:details.html.twig', array(
        'form' => $form->createView(),
        'commande_id'=> $commandeId,
        'commande_nbBillet' => $commandeNbBillet
    ));
    }
    
    public function paymentAction()
    {
       
      return $this->render('LouvreBilletterieBundle:Booking:payment.html.twig');
    }

}
