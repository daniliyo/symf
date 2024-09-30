<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Enum\DivisionEnum;
use App\Service\ResetTableService;
use App\Service\CreateDivisionService;
use App\Service\CreatePlayOffService;
use App\Service\RenderTableService;

class TableController extends AbstractController
{
    #[Route('/table', name: 'app_table')]
    public function index(array $messages = []): Response
    {
        return $this->render('table/index.html.twig', $messages);
    }

    #[Route('/table/generate', name: 'app_table_generate')]
    public function generate(
        ResetTableService $res, 
        CreateDivisionService $divisionService,
        CreatePlayOffService $playOffService,
    ): Response
    {
        try {
            $res->reset();
            
            $divisionService->create(DivisionEnum::DIVISION_A);
            $divisionService->create(DivisionEnum::DIVISION_B);

            $playOffService->create();

            $messages['success'] = 'Данные успешно сгенирированы';
        }catch (\Throwable $exception) {
            $messages['error'] = 'Произошла ошибка, попробуйте позже';
            //$messages['error'] .= "<br>Text: ".$exception->getMessage();
            //$messages['error'] .= 'Line:'.$exception->getLine();
            //$messages['error'] .= 'File:'.$exception->getFine();
        }

        return $this->index(messages: $messages);
    }

    #[Route('/table/public', name: 'app_table_public')]
    public function public(RenderTableService $table): Response
    {
        $data = $table->getData();

        return $this->render('table/public.html.twig', $data);
    }
}
