<?php

namespace App\Form;

use App\Entity\InfoCollaborators;
use App\Repository\InfoCollaboratorsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoCollaboratorsType extends AbstractType
{
    private $infoCollaboratorsRepository;

    public function __construct(InfoCollaboratorsRepository $infoCollaboratorsRepository)
    {
        $this->infoCollaboratorsRepository = $infoCollaboratorsRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule', ChoiceType::class, [
                'placeholder' => 'Choisir un matricule',
                'choices' => $this->infoCollaboratorsRepository->findColumnOneByOne('matricule'),
                'required' => false,
            ])
            ->add('name', ChoiceType::class, [
                'placeholder' => 'Choisir un name',
                'choices' => $this->infoCollaboratorsRepository->findColumnOneByOne('name'),
                'required' => false,
            ])
            ->add('firstname', ChoiceType::class, [
                'placeholder' => 'Choisir un firstname',
                'choices' => $this->infoCollaboratorsRepository->findColumnOneByOne('firstname'),
                'required' => false,
            ])
            ->add('position', ChoiceType::class, [
                'placeholder' => 'Choisir un position',
                'choices' => $this->infoCollaboratorsRepository->findColumnOneByOne('position'),
                'required' => false,
            ])
            ->add('csp', ChoiceType::class, [
                'placeholder' => 'Choisir un csp',
                'choices' => $this->infoCollaboratorsRepository->findColumnOneByOne('csp'),
                'required' => false,
            ])
            ->add('reset', SubmitType::class, [
                'attr' => ['class' => 'reset-button'],
                'label' => 'Réinitialiser',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {

            $data = $event->getData();
            $form = $event->getForm();
            
            unset($data['reset']);

            $tabChamp = array_keys($data);

            foreach ($data as $dataKey => $dataValue) {
                if ($dataValue !== "") {
                    foreach ($tabChamp as $tabChampKey => $tabChampValue) {
                        if ($tabChampValue !== $dataKey) {
                            $form->add($tabChampValue, ChoiceType::class, [
                                'placeholder' => 'Choisir un '.$tabChampValue,
                                'choices' => $this->infoCollaboratorsRepository->findColumnOneByOne($tabChampValue, $data),
                                'required' => false,
                            ]);
                        }
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