<?php

namespace App\Controller\Back;

use App\Entity\CustomPage;
use App\Form\CustomPageType;
use App\Repository\CustomPageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/custom-page')]
class CustomPageController extends AbstractController
{
    private CustomPageRepository $customPageRepository;
    private EntityManagerInterface $em;

    /**
     * @param CustomPageRepository $customPageRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(CustomPageRepository $customPageRepository, EntityManagerInterface $em)
    {
        $this->customPageRepository = $customPageRepository;
        $this->em = $em;
    }

    #[Route('/', name: 'custom_page_index', methods: ['GET'])]
    public function index(): Response
    {
        $customPages = $this->customPageRepository->findAll();
        return $this->render('back/page/custom_page/index.html.twig', [
            'custom_pages' => $customPages,
        ]);
    }

    #[Route('/new', name: 'custom_page_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $customPage = new CustomPage();
        $form = $this->createForm(CustomPageType::class, $customPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($customPage);
            $this->em->flush();

            return $this->redirectToRoute('custom_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/page/custom_page/new.html.twig', [
            'custom_page' => $customPage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'custom_page_show', methods: ['GET'])]
    public function show(CustomPage $customPage): Response
    {
        return $this->render('back/page/custom_page/show.html.twig', [
            'custom_page' => $customPage,
        ]);
    }

    #[Route('/{id}/edit', name: 'custom_page_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CustomPage $customPage): Response
    {
        $form = $this->createForm(CustomPageType::class, $customPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('custom_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('custom_page/edit.html.twig', [
            'custom_page' => $customPage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'custom_page_delete', methods: ['POST'])]
    public function delete(Request $request, CustomPage $customPage): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customPage->getId(), $request->request->get('_token'))) {
            $this->em->remove($customPage);
            $this->em->flush();
        }

        return $this->redirectToRoute('custom_page_index', [], Response::HTTP_SEE_OTHER);
    }
}
