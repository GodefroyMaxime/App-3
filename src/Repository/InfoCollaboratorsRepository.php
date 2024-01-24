<?php

namespace App\Repository;

use App\Entity\InfoCollaborators;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfoCollaborators>
 *
 * @method InfoCollaborators|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoCollaborators|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoCollaborators[]    findAll()
 * @method InfoCollaborators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoCollaboratorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoCollaborators::class);
    }

   /**
    * @return InfoCollaborators Returns a query of InfoCollaborators objects
    */
   public function paginationQuery():  \Doctrine\ORM\QueryBuilder
   {
       return $this->createQueryBuilder('i')
           ->orderBy('i.id', 'ASC')
           //->getQuery()
       ;
   }

   public function findColumnOneByOne($column, array $parameters = [])
    {
        $query = $this->createQueryBuilder('i')
            ->select("DISTINCT i." . $column);

        if (!empty($parameters)) {
            foreach ($parameters as $key => $value) {
                $query->andWhere('i.' . $key . ' = :' . $key)
                    ->setParameter(':' . $key, $value);
            }
        }
        
        $result = $query->getQuery()->getResult();
    
        return array_column($result, $column, $column);
    }

//    public function findOneBySomeField($value): ?InfoCollaborators
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
