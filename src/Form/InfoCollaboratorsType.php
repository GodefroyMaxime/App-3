<?php

namespace App\Form;

use App\Entity\InfoCollaborators;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class InfoCollaboratorsType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position', ChoiceType::class, [
                'choices' => $this->getUniqueData('position'),
                'placeholder' => 'Choisir un position',
                'required' => false,
            ])
            ->add('name', ChoiceType::class, [
                'choices' => $this->getUniqueData('name'),
                'placeholder' => 'Choisir un nom',
                'required' => false,
            ])
            ->add('firstname', ChoiceType::class, [
                'choices' => $this->getUniqueData('firstname'),
                'placeholder' => 'Choisir un prénom',
                'required' => false,
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $this->setupDynamicFields($event->getForm());
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $this->setupDynamicFields($event->getForm(), $event->getData());
            }
        );
    }

    private function setupDynamicFields(FormInterface $form, $data = null)
    {
        $position = is_array($data) && !empty($data['position']) ? $data['position'] : null;
        $name = is_array($data) && !empty($data['name']) ? $data['name'] : null;
        $firstname = is_array($data) && !empty($data['firstname']) ? $data['firstname'] : null;

        if ($position != null) {
            // $form->remove('position');
            // $form->remove('name');
            // $form->remove('firstname');
            $choices = $this->getFilteredData('position', $position, $name, $firstname);
            $form->add('name', ChoiceType::class, [
                'choices' => $choices['name'],
                'placeholder' => 'Choisir un nom',
                'required' => false,
            ]);
            $form->add('firstname', ChoiceType::class, [
                'choices' => $choices['firstname'],
                'placeholder' => 'Choisir un prénom',
                'required' => false,
            ]);
        }

        if ($name != null) {
            // $form->remove('position');
            // $form->remove('name');
            // $form->remove('firstname');
            $choices = $this->getFilteredData('name', $position, $name, $firstname);
            $form->add('position', ChoiceType::class, [
                'choices' => $choices['position'],
                'placeholder' => 'Choisir un position',
                'required' => false,
            ]);
            $form->add('firstname', ChoiceType::class, [
                'choices' => $choices['firstname'],
                'placeholder' => 'Choisir un prénom',
                'required' => false,
            ]);
        }

        if ($firstname != null) {
            // $form->remove('position');
            // $form->remove('name');
            // $form->remove('firstname');
            $choices = $this->getFilteredData('firstname', $position, $name, $firstname);
            $form->add('position', ChoiceType::class, [
                'choices' => $choices['position'],
                'placeholder' => 'Choisir un position',
                'required' => false,
            ]);
            $form->add('name', ChoiceType::class, [
                'choices' => $choices['name'],
                'placeholder' => 'Choisir un nom',
                'required' => false,
            ]);
        }
    }

    private function getFilteredData($fieldToFilter, $position = null, $name = null, $firstname = null)
    {
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select("DISTINCT e")
                    ->from('App\Entity\InfoCollaborators', 'e');

        if ($position !== null) {
            $queryBuilder->andWhere('e.position = :position')
                        ->setParameter('position', $position);
        }

        if ($name !== null) {
            $queryBuilder->andWhere('e.name = :name')
                        ->setParameter('name', $name);
        }

        if ($firstname !== null) {
            $queryBuilder->andWhere('e.firstname = :firstname')
                        ->setParameter('firstname', $firstname);
        }

        $results = $queryBuilder->getQuery()->getResult();

        $choices['position'] = [];
        $choices['name'] = [];
        $choices['firstname'] = [];
        $serializer = new Serializer([new ObjectNormalizer()]);
        foreach ($results as $result) {
            $filterData = $serializer->normalize($result);
            if($fieldToFilter == 'position') {
                $choices['name'] += [$filterData['name'] => $filterData['name']];
                $choices['firstname'] += [$filterData['firstname'] => $filterData['firstname']];
            }
            if($fieldToFilter == 'name') {
                $choices['position'] += [$filterData['position'] => $filterData['position']];
                $choices['firstname'] += [$filterData['firstname'] => $filterData['firstname']];
            }
            if($fieldToFilter == 'firstname') {
                $choices['position'] += [$filterData['position'] => $filterData['position']];
                $choices['name'] += [$filterData['name'] => $filterData['name']];
            }
        }

        return $choices;
    }


    private function getUniqueData($fieldName)
    {
        $query = $this->entityManager->createQuery(
            "SELECT DISTINCT e.$fieldName FROM App\Entity\InfoCollaborators e"
        );
        $results = $query->getResult();

        $choices = [];
        foreach ($results as $result) {
            if (!is_array($result) || !isset($result[$fieldName])) {
                continue;
            }
        
            $value = $result[$fieldName];
            if (!is_scalar($value)) {
                continue;
            }
        
            $choices[$value] = $value;
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfoCollaborators::class,
        ]);
    }
}
