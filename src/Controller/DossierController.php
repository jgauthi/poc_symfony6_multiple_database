<?php
namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Enum\DossierStatusEnum;
use App\Repository\DossierRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class DossierController extends AbstractController
{
    public function __construct(private DossierRepository $dossierRepository)
    {
    }

    #[Route('/', name: 'dossierList', methods: [Request::METHOD_GET])]
    public function dossierList(Request $request, PaginatorInterface $paginator): Response
    {
        $query = $this->dossierRepository->findByStatus(DossierStatusEnum::ACTIVE);
        $dossierList = $paginator->paginate($query, $request->query->getInt('page', 1), 6);

        return $this->render('dossierList.html.twig', [
            'dossierList' => $dossierList,
        ]);
    }

    #[Route('/dossier/{id}', name: 'dossierItem', methods: [Request::METHOD_GET], requirements: ['id' => '\d+'])]
    public function dossierItem(Dossier $dossier): Response
    {
        return $this->render('dossierItem.html.twig', [
            'dossier' => $dossier,
        ]);
    }
}
