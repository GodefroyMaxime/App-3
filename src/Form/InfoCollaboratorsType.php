<?php

namespace App\Form;

use App\Entity\InfoCollaborators;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;

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
            ->add('matricule', ChoiceType::class, [
                'choices' => $this->getUniqueData('matricule'),
                'placeholder' => 'Choisir un matricule',
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
            ])
            ->add('position', ChoiceType::class, [
                'choices' => $this->getUniqueData('position'),
                'placeholder' => 'Choisir une position',
                'required' => false,
            ])
            ->add('seniority', ChoiceType::class, [
                'choices' => $this->getUniqueData('seniority'),
                'placeholder' => 'Choisir une ancienneté',
                'required' => false,
            ])
            ->add('gross_annual_salary', ChoiceType::class, [
                'choices' => $this->getUniqueData('gross_annual_salary'),
                'placeholder' => 'Choisir un salaire brut annuel',
                'required' => false,
            ])
            ->add('net_annual_salary', ChoiceType::class, [
                'choices' => $this->getUniqueData('net_annual_salary'),
                'placeholder' => 'Choisir un salaire net annuel',
                'required' => false,
            ])
            ->add('bonus', ChoiceType::class, [
                'choices' => $this->getUniqueData('bonus'),
                'placeholder' => 'Choisir un bonus',
                'required' => false,
            ])
            ->add('employee_share_mutual_insurance', ChoiceType::class, [
                'choices' => $this->getUniqueData('employee_share_mutual_insurance'),
                'placeholder' => 'Choisir une part salariale d’assurance mutuelle',
                'required' => false,
            ])
            ->add('employer_share_mutual_insurance', ChoiceType::class, [
                'choices' => $this->getUniqueData('employer_share_mutual_insurance'),
                'placeholder' => 'Choisir une part patronale d’assurance mutuelle',
                'required' => false,
            ])
            ->add('employee_share_health_insurance', ChoiceType::class, [
                'choices' => $this->getUniqueData('employee_share_health_insurance'),
                'placeholder' => 'Choisir une part salariale d’assurance santé',
                'required' => false,
            ])
            ->add('employer_share_health_insurance', ChoiceType::class, [
                'choices' => $this->getUniqueData('employer_share_health_insurance'),
                'placeholder' => 'Choisir une part patronale d’assurance santé',
                'required' => false,
            ])
            ->add('employee_share_pension', ChoiceType::class, [
                'choices' => $this->getUniqueData('employee_share_pension'),
                'placeholder' => 'Choisir une part salariale de pension',
                'required' => false,
            ])
            ->add('employer_share_pension', ChoiceType::class, [
                'choices' => $this->getUniqueData('employer_share_pension'),
                'placeholder' => 'Choisir une part patronale de pension',
                'required' => false,
            ])
            ->add('csp', ChoiceType::class, [
                'choices' => $this->getUniqueData('csp'),
                'placeholder' => 'Choisir une CSP',
                'required' => false,
            ])
        ;
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
