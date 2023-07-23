<?php

namespace App\Controller;

use App\Entity\Device;
use App\Form\DeviceType;
use App\Repository\DeviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/device', name: 'device_')]
class DeviceController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(DeviceRepository $deviceRepository): Response
    {
        return $this->render('device/index.html.twig', [
            'devices' => $deviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $device = new Device();
        $form = $this->createForm(DeviceType::class, $device);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($device);
            $entityManager->flush();

            return $this->redirectToRoute('app_device_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('device/new.html.twig', [
            'device' => $device,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Device $device,  ChartBuilderInterface $chartBuilder): Response
    {
        $values= [];
        foreach ($device->getHistories()->getValues() as $deviceHistory) {
            $values[] = $deviceHistory->getValue();
        }
        $timestamp = [];
        foreach ($device->getHistories()->getValues() as $deviceHistory) {
            $timestamp[] = $deviceHistory->getCreatedAt()->format('d/m/Y - H:i');
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            
            
            'labels' => $timestamp,
            
            'datasets' => [
                [
                    'label' => 'min',
                    'backgroundColor' => 'rgb(245, 40, 145)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [$device->getType()->getBounds()[0]],
                ],
                [
                    'label' => 'max',
                    'backgroundColor' => 'rgb(245, 40, 145)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [$device->getType()->getBounds()[1]],
                ],
                [
                    'label' => 'actual',
                    'backgroundColor' => 'rgb(39, 63, 245)',
                    'borderColor' => 'rgb(39, 63, 245)',
                    'data' => $values,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);
        
        return $this->render('device/show.html.twig', [
            'device' => $device,
            'chart'  => $chart,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Device $device, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeviceType::class, $device);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_device_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('device/edit.html.twig', [
            'device' => $device,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Device $device, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$device->getId(), $request->request->get('_token'))) {
            $entityManager->remove($device);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_device_index', [], Response::HTTP_SEE_OTHER);
    }
}
