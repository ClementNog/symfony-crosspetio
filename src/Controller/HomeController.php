<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Race;
use App\Form\RaceType;
use App\Repository\RaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/home')]
class HomeController extends AbstractController
{
    
    #[Route('/', name: 'app_race_index', methods: ['GET'])]
    public function index(RaceRepository $raceRepository): Response
    {
        $message = "";

        return $this->render('race/index.html.twig', [
            'races' => $raceRepository->findAll(),
            'message' => $message,
        ]);
    }

    #[Route('/new', name: 'app_race_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RaceRepository $raceRepository, SluggerInterface $slugger): Response
    {
        $message = "";

        $race = new Race();
        $form = $this->createForm(RaceType::class, $race);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Import CSV file
            $filenameFile = $form->get('filename')->getData();
            $message .= "filenameFile = '" . $filenameFile . "'";

            // this condition is needed because the 'filename' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($filenameFile) {
                $originalFilename = pathinfo($filenameFile->getClientOriginalName(), PATHINFO_FILENAME);
                $message .="originalFilename = '" . $originalFilename . "'";

                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $message .="safeFilename = '" . $safeFilename . "'";

                $newFilename = $safeFilename . '-' . uniqid() . '.' . $filenameFile->guessExtension();
                $message .= "newFilename = '" . $newFilename . "'";

                // Move the file to the directory where filenames are stored
                try {
                    $filenameFile->move(
                        $this->getParameter('filenames_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'filenameFilename' property to store the PDF file name
                // instead of its contents
                $race->setFilename($newFilename);
            }

            $raceRepository->save($race, true);

            return $this->render('race/index.html.twig', [
                'races' => $raceRepository->findAll(),
                'message' => $message,
            ]);

        }

        return $this->renderForm('race/new.html.twig', [
            'race' => $race,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_race_show', methods: ['GET'])]
    public function show(Race $race): Response
    {
        return $this->render('race/show.html.twig', [
            'race' => $race,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_race_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Race $race, RaceRepository $raceRepository): Response
    {
        $form = $this->createForm(RaceType::class, $race);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $raceRepository->save($race, true);

            return $this->redirectToRoute('app_race_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('race/edit.html.twig', [
            'race' => $race,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_race_delete', methods: ['POST'])]
    public function delete(Request $request, Race $race, RaceRepository $raceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $race->getId(), $request->request->get('_token'))) {
            $raceRepository->remove($race, true);
        }

        return $this->redirectToRoute('app_race_index', [], Response::HTTP_SEE_OTHER);
    }
}
