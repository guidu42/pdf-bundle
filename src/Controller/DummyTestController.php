<?php

namespace App\Controller;

use App\Entity\DummyTest;
use App\Form\DummyTestType;
use App\Repository\DummyTestRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dummy/test')]
class DummyTestController extends AbstractController
{
    public function __construct(
        private PaginatorInterface $paginator,
    ) {
    }


    #[Route('/', name: 'dummy_test_index', methods: ['GET'])]
    public function index(DummyTestRepository $dummyTestRepository, Request $request): Response
    {

        $qb = $dummyTestRepository->getQbAll();
        $pagination = $this->paginator->paginate($qb, $request->query->getInt('page', 1), 4);
        return $this->render('dummy_test/index.html.twig', [
            'dummy_tests' => $pagination,
        ]);
    }

    #[Route('/new', name: 'dummy_test_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $dummyTest = new DummyTest();
        $form = $this->createForm(DummyTestType::class, $dummyTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dummyTest);
            $entityManager->flush();

            return $this->redirectToRoute('dummy_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dummy_test/new.html.twig', [
            'dummy_test' => $dummyTest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'dummy_test_show', methods: ['GET'])]
    public function show(DummyTest $dummyTest): Response
    {
        return $this->render('dummy_test/show.html.twig', [
            'dummy_test' => $dummyTest,
        ]);
    }

    #[Route('/{id}/edit', name: 'dummy_test_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DummyTest $dummyTest): Response
    {
        $form = $this->createForm(DummyTestType::class, $dummyTest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dummy_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dummy_test/edit.html.twig', [
            'dummy_test' => $dummyTest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'dummy_test_delete', methods: ['POST'])]
    public function delete(Request $request, DummyTest $dummyTest): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dummyTest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dummyTest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dummy_test_index', [], Response::HTTP_SEE_OTHER);
    }
}
