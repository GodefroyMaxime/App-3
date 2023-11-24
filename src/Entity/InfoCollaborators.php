<?php

namespace App\Entity;

use App\Repository\InfoCollaboratorsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfoCollaboratorsRepository::class)]
class InfoCollaborators
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $matricule = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $position = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $seniority = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $gross_annual_salary = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $net_annual_salary = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $bonus = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $employee_share_mutual_insurance = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $employer_share_mutual_insurance = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $employee_share_health_insurance = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $employer_share_health_insurance = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $employee_share_pension = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $employer_share_pension = null;

    #[ORM\Column(length: 255)]
    private ?string $csp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?int
    {
        return $this->matricule;
    }

    public function setMatricule(int $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getSeniority(): ?\DateTimeInterface
    {
        return $this->seniority;
    }

    public function setSeniority(\DateTimeInterface $seniority): static
    {
        $this->seniority = $seniority;

        return $this;
    }

    public function getGrossAnnualSalary(): ?string
    {
        return $this->gross_annual_salary;
    }

    public function setGrossAnnualSalary(string $gross_annual_salary): static
    {
        $this->gross_annual_salary = $gross_annual_salary;

        return $this;
    }

    public function getNetAnnualSalary(): ?string
    {
        return $this->net_annual_salary;
    }

    public function setNetAnnualSalary(string $net_annual_salary): static
    {
        $this->net_annual_salary = $net_annual_salary;

        return $this;
    }

    public function getBonus(): ?string
    {
        return $this->bonus;
    }

    public function setBonus(string $bonus): static
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getEmployeeShareMutualInsurance(): ?string
    {
        return $this->employee_share_mutual_insurance;
    }

    public function setEmployeeShareMutualInsurance(string $employee_share_mutual_insurance): static
    {
        $this->employee_share_mutual_insurance = $employee_share_mutual_insurance;

        return $this;
    }

    public function getEmployerShareMutualInsurance(): ?string
    {
        return $this->employer_share_mutual_insurance;
    }

    public function setEmployerShareMutualInsurance(string $employer_share_mutual_insurance): static
    {
        $this->employer_share_mutual_insurance = $employer_share_mutual_insurance;

        return $this;
    }

    public function getEmployeeShareHealthInsurance(): ?string
    {
        return $this->employee_share_health_insurance;
    }

    public function setEmployeeShareHealthInsurance(string $employee_share_health_insurance): static
    {
        $this->employee_share_health_insurance = $employee_share_health_insurance;

        return $this;
    }

    public function getEmployerShareHealthInsurance(): ?string
    {
        return $this->employer_share_health_insurance;
    }

    public function setEmployerShareHealthInsurance(string $employer_share_health_insurance): static
    {
        $this->employer_share_health_insurance = $employer_share_health_insurance;

        return $this;
    }

    public function getEmployeeSharePension(): ?string
    {
        return $this->employee_share_pension;
    }

    public function setEmployeeSharePension(string $employee_share_pension): static
    {
        $this->employee_share_pension = $employee_share_pension;

        return $this;
    }

    public function getEmployerSharePension(): ?string
    {
        return $this->employer_share_pension;
    }

    public function setEmployerSharePension(string $employer_share_pension): static
    {
        $this->employer_share_pension = $employer_share_pension;

        return $this;
    }

    public function getCsp(): ?string
    {
        return $this->csp;
    }

    public function setCsp(string $csp): static
    {
        $this->csp = $csp;

        return $this;
    }
}
