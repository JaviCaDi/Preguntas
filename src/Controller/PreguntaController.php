<?php
namespace App\Controller;

use App\Entity\Pregunta;
use App\Form\PreguntaType;
use App\Repository\PreguntaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PreguntaController extends AbstractController
{
    #[Route('/admin/preguntas', name: 'admin_pregunta_index', methods: ['GET'])]
    public function index(PreguntaRepository $preguntaRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $preguntasQuery = $preguntaRepository->createQueryBuilder('p')
            ->orderBy('p.fechaInicio', 'DESC')
            ->getQuery();

        $preguntas = $paginator->paginate(
            $preguntasQuery,
            $request->query->getInt('page', 1),
            10 // Número de preguntas por página
        );

        return $this->render('pregunta/admin_index.html.twig', [
            'preguntas' => $preguntas,
        ]);
    }

    #[Route('/admin/preguntas/nueva', name: 'admin_pregunta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pregunta = new Pregunta();
        $form = $this->createForm(PreguntaType::class, $pregunta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persistimos la nueva pregunta y las respuestas asociadas
            $entityManager->persist($pregunta);
            $entityManager->flush();

            $this->addFlash('success', 'Pregunta creada correctamente.');

            return $this->redirectToRoute('admin_pregunta_index');
        }

        return $this->render('pregunta/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/preguntas/{id}', name: 'admin_pregunta_show', methods: ['GET'])]
    public function show(Pregunta $pregunta): Response
    {
        return $this->render('pregunta/show.html.twig', [
            'pregunta' => $pregunta,
        ]);
    }

    #[Route('/admin/preguntas/{id}/editar', name: 'admin_pregunta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pregunta $pregunta, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PreguntaType::class, $pregunta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si el formulario es válido, actualizamos la pregunta y las respuestas asociadas
            $entityManager->flush();

            $this->addFlash('success', 'Pregunta actualizada correctamente.');

            return $this->redirectToRoute('admin_pregunta_index');
        }

        return $this->render('pregunta/edit.html.twig', [
            'form' => $form->createView(),
            'pregunta' => $pregunta,
        ]);
    }

    #[Route('/admin/preguntas/{id}', name: 'admin_pregunta_delete', methods: ['POST'])]
    public function delete(Request $request, Pregunta $pregunta, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pregunta->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pregunta);
            $entityManager->flush();

            $this->addFlash('success', 'Pregunta eliminada correctamente.');
        }

        return $this->redirectToRoute('admin_pregunta_index');
    }
}
