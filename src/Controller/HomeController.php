<?php

namespace App\Controller;

use App\Repository\DeviceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(DeviceRepository $deviceRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'deviceCount' => $deviceRepo->countAllDevices(),
            // 'alertCount'  => $alertRepo->countAllAlerts(),
            'now'         => new \DateTime(),
        ]);
    }
}
