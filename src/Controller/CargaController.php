<?php

namespace App\Controller;

use App\Entity\Persona;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CargaController extends AbstractController
{
    /**
     * @Route("/carga", name="app_carga")
     */
    public function index(Request $request, EntityManagerInterface $entityManager,
                            ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();


        $form = $this->createFormBuilder()
        ->add('apellido', TextType::class, ['attr' => ['placeholder' => 'APELLIDO','style'=>'width:200px']])
        ->add('nombre', TextType::class, ['attr' => ['placeholder' => 'NOMBRE','style'=>'width:200px']])
        ->add('dni', TextType::class, ['attr' => ['placeholder' => 'DNI','style'=>'width:100px']])
        ->add('jornada', TextType::class, ['attr' => ['placeholder' => 'JORNADA','style'=>'width:100px']])
        ->add('enviar', SubmitType::class)
        ->getForm();

        $lista=$entityManager->getRepository( Persona::class )->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $persona = new Persona();

            $datos = $form->getData();

            $persona->setApellido($datos['apellido']);
            $persona->setNombre($datos['nombre']);
            $persona->setDni($datos['dni']);
            $persona->setJornada($datos['jornada']);


            $entityManager->persist($persona);
            $entityManager->flush();

            $lista=$entityManager->getRepository( Persona::class )->findAll();

            return $this->render('carga/index.html.twig', [
                'form' => $form->createView(),
                'lista'=>$lista
            ]);
    }

        return $this->render('carga/index.html.twig', [
            'form' => $form->createView(),
            'lista'=>$lista
        ]);
    }
}
