<?php

namespace App\Form;

use App\Entity\InfoCollaborators;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoCollaborators1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('name')
            ->add('firstname')
            ->add('position')
            ->add('seniority')
            ->add('gross_annual_salary')
            ->add('net_annual_salary')
            ->add('bonus')
            ->add('employee_share_mutual_insurance')
            ->add('employer_share_mutual_insurance')
            ->add('employee_share_health_insurance')
            ->add('employer_share_health_insurance')
            ->add('employee_share_pension')
            ->add('employer_share_pension')
            ->add('csp')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfoCollaborators::class,
        ]);
    }
}
