<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Credit;
use App\Form\ClientType;
use App\Form\CreditType;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreditController extends AbstractController
{
    /**
     * @Route("/credits", name="show_credits")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllCreditsAction(Request $request)
    {
        $credits = $this->getDoctrine()->getRepository(Credit::class)->findAll();
        return $this->render('show_credits.html.twig', [
            'credits' => $credits
        ]);
    }

    /**
     * @Route("/credits/{id}/get_a_loan", name="get_a_loan")
     * @param Request $request
     * @param $id
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function getALoanAction(Request $request, $id, ObjectManager $manager)
    {
        $client = $this->getDoctrine()->getRepository(Client::class)->find($id);

        if (!$client->lastCreditIsPaid()) {
            return $this->redirect(
                $request
                    ->headers
                    ->get('referer')
            );
        }

        $credit = new Credit();
        $credit->setClient($client);

        $form = $this->createForm(CreditType::class, $credit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $credit->prePersist();
            $manager->persist($credit);
            $manager->flush();
            return $this->redirectToRoute("show_details_current_credit", ['id' => $id]);
        }

        return $this->render('get_a_loan.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/client/{id}/profile", name="client_profile")
     * @param Request $request
     * @param Integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function clientProfileAction(Request $request, $id)
    {
        $client = $this->getDoctrine()->getRepository(Client::class)->find($id);
        return $this->render('client_profile.html.twig', [
            'client' => $client
        ]);
    }

    /**
     * @Route("/client/{id}/change_status", name="change_status")
     * @param Request $request
     * @param Integer $id
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeStatusAction(Request $request, $id, ObjectManager $manager)
    {
        $client = $this->getDoctrine()->getRepository(Client::class)->find($id);
        $status = $client->getStatus();
        if($status) {
            $client->setStatus(0);
        } else {
            $client->setStatus(1);
        }
        $manager->persist($client);
        $manager->flush();
        return $this->redirectToRoute('show_clients');
    }

    /**
     * @Route("/client/create", name="create_client")
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createClientAction(Request $request, ObjectManager $manager)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($client);
            $manager->flush();
            return $this->redirectToRoute("show_clients");
        }
        return $this->render('create_client.html.twig', [
            'form' => $form->createView()
        ]);
    }

}