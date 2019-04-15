<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/clients", name="show_clients")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showClientsAction(Request $request)
    {
        $clients = $this->getDoctrine()->getRepository(Client::class)->findAll();
        return $this->render('show_clients.html.twig', [
            'clients' => $clients
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