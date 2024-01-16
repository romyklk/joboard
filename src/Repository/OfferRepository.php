<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    //    /**
    //     * @return Offer[] Returns an array of Offer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Offer
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findPaginatedOffers(int $page ,int $limit= 9): array
    {
        $limit = abs($limit);

        $result = [];

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('o')
            ->from(Offer::class, 'o')
            ->join('o.entreprise', 'e')
            ->join('o.contractType', 'c')
            ->where('o.isActive = true')
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit) - $limit);

            $paginator = new Paginator($query);

            $data = $paginator->getQuery()->getResult();

            // Vérifier si les données sont vides
            if(empty($data)) {
                return $result;
            }

            //Calculer le nombre de pages
            $pages = ceil($paginator->count() / $limit);
            
            // Récupérer les données
            $result = [
                'data' => $data, // Contient les données
                'pages' => $pages, // Contient le nombre de pages
                'page' => $page, // Contient la page actuelle
                'limit' => $limit // Contient le nombre d'éléments par page
            ];

        return $result;
    }
}
