<?php

namespace App\Controller;

use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends AbstractController
{
    #[Route('/chartjs', name: 'app_chartjs')]
    public function chartjs(ChartBuilderInterface $chartBuilder)
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'datasets' => [
                [
                    'label' => '# of commits on my GitHub account per month',
                    'backgroundColor' => 'rgb(45, 6, 124, .7)',
                    'borderColor' => 'rgb(45, 6, 124)',
                    'data' => [0, 9, 12, 19, 7, 14, 0, 3, 12, 26, 147],
                    'tension' => 0.5,
                ],
                [
                    'label' => '# of repositories created on my GitHub account per month',
                    'backgroundColor' => 'rgba(45, 220, 126, .4)',
                    'borderColor' => 'rgba(45, 220, 126)',
                    'data' => [0, 5, 3, 2, 0, 1, 0, 3, 6, 10, 2],
                    'tension' => 0.4,
                ],
            ],
        ]);
        $chart->setOptions([
            'maintainAspectRatio' => false,
        ]);

        return $this->render('chart/chartjs.html.twig', ['chart' => $chart,]);
    }
}
