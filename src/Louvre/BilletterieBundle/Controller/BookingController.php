<?php
// src/Louvre/BilletterieBundle/Controller/BookingController.php

namespace Louvre\BilletterieBundle\Controller;

use Louvre\BilletterieBundle\Entity\Commande;
use Louvre\BilletterieBundle\Form\CommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends Controller
{
    public function commandeAction(Request $request)
        
    {
        $commande = new Commande();
        $form = $this->get('form.factory')->create(CommandeType::class, $commande);
        

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $commandePrixTotal = 0;
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Commande bien enregistrée.');

      return $this->redirectToRoute('louvre_billetterie_details', array('commande_id' => $commande->getCommandeId()));
    }

    return $this->render('LouvreBilletterieBundle:Booking:commande.html.twig', array(
      'form' => $form->createView(),
    ));
  }
    
    public function detailsAction()
  {

    return $this->render('LouvreBilletterieBundle:Booking:details.html.twig');
  }

}
