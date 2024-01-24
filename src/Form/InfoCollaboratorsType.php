<?php

namespace App\Form;

use App\Entity\InfoCollaborators;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use function PHPUnit\Framework\isEmpty;

class InfoCollaboratorsType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $infoRepository = $this->entityManager->getRepository(InfoCollaborators::class);
        $builder
            ->add('matricule', ChoiceType::class, [
                'placeholder' => 'Choisir un matricule',
                'required' => false,
                'choices' => $infoRepository->findColumnOneByOne('matricule'),
            ])
            ->add('name', ChoiceType::class, [
                'placeholder' => 'Choisir un name',
                'required' => false,
                'choices' => $infoRepository->findColumnOneByOne('name'),
            ])
            ->add('firstname', ChoiceType::class, [
                'placeholder' => 'Choisir un firstname',
                'required' => false,
                'choices' => $infoRepository->findColumnOneByOne('firstname'),
            ])
            ->add('position', ChoiceType::class, [
                'placeholder' => 'Choisir un position',
                'required' => false,
                'choices' => $infoRepository->findColumnOneByOne('position'),
            ])
            ->add('csp', ChoiceType::class, [
                'placeholder' => 'Choisir un csp',
                'required' => false,
                'choices' => $infoRepository->findColumnOneByOne('csp'),
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data) {
                foreach ($data as $key => $value) {
                    if($value == "") {
                        $form->add($key, ChoiceType::class, [
                            'choices' => $this->entityManager->getRepository(InfoCollaborators::class)->findColumnOneByOne($key, $data),
                        ]);
                    }
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            if (empty($filterData)) {
                foreach ($data as $key => $value) {
                    if($value == "") {
                        $form->add($key, ChoiceType::class, [
                            'choices' => $this->entityManager->getRepository(InfoCollaborators::class)->findColumnOneByOne($key, $data),
                        ]);
                    }
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfoCollaborators::class,
        ]);
    }
}