<?php

namespace GS\MailerBundle\Repository;

/**
 * MailRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MailRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByLike($pattern, $sortCode)
    {
      $qb = $this->createQueryBuilder('a')
        ->leftJoin('a.user', 'u')
        ->leftJoin('a.state', 's')
        ->where('a.recipientEmail LIKE :pattern')
        ->setParameter('pattern', '%'.$pattern.'%')
      ;

      if($sortCode[0] == 1)
          $qb->orderBy('a.recipientEmail', 'ASC');
      else if($sortCode[0] == -1)
          $qb->orderBy('a.recipientEmail', 'DESC');
      else if($sortCode[1] == 1)
          $qb->orderBy('u.firstName', 'ASC');
      else if($sortCode[1] == -1)
          $qb->orderBy('u.firstName', 'DESC');
      else if($sortCode[2] == 1)
          $qb->orderBy('a.sentAt', 'DESC');
      else if($sortCode[2] == -1)
          $qb->orderBy('a.sentAt', 'ASC');
      else if($sortCode[3] == 1)
          $qb->orderBy('s.sortCode', 'DESC');
      else if($sortCode[3] == -1)
          $qb->orderBy('s.sortCode', 'ASC');

      // $qb->orderBy('a.sentAt', 'DESC');  //Dans tous les cas on tri par date croissante

      return $qb
        ->getQuery()
        ->getResult()
      ;
    }

    public function findByLikeAdvanced($recipientEmailPattern, $fromPattern, $datePattern, $sortByState, $stateId, $sortCode)
    {
      $qb = $this->createQueryBuilder('a')
        ->leftJoin('a.user', 'u')
        ->leftJoin('a.state', 's')
        ->where('a.recipientEmail LIKE :recipientEmailPattern')
        ->setParameter('recipientEmailPattern', '%'.$recipientEmailPattern.'%')
        ->andWhere('u.firstName LIKE :fromPattern OR u.lastName LIKE :fromPattern')
        // ->orWhere('CONCAT(u.firstName, "", u.lastName) LIKE :fromPattern')
        ->setParameter('fromPattern', '%'.$fromPattern.'%')
        ->andWhere('a.sentAt LIKE :datePattern')
        ->setParameter('datePattern', '%'.$datePattern.'%')
      ;

      if($sortByState && $stateId != null){
          $qb->andWhere('s.id = :idState')
            ->setParameter('idState', $stateId)
          ;
      }
      else if($sortByState){
          $qb->andWhere('a.state IS NULL');
      }

      if($sortCode[0] == 1)
          $qb->orderBy('a.recipientEmail', 'ASC');
      else if($sortCode[0] == -1)
          $qb->orderBy('a.recipientEmail', 'DESC');
      else if($sortCode[1] == 1)
          $qb->orderBy('u.firstName', 'ASC');
      else if($sortCode[1] == -1)
          $qb->orderBy('u.firstName', 'DESC');
      else if($sortCode[2] == 1)
          $qb->orderBy('a.sentAt', 'DESC');
      else if($sortCode[2] == -1)
          $qb->orderBy('a.sentAt', 'ASC');
      else if($sortCode[3] == 1)
          $qb->orderBy('s.sortCode', 'DESC');
      else if($sortCode[3] == -1)
          $qb->orderBy('s.sortCode', 'ASC');

      // $qb->orderBy('a.sentAt', 'DESC');  //Dans tous les cas on tri par date croissante

      return $qb
        ->getQuery()
        ->getResult()
      ;
    }

    public function countAll()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(mail.id)')
            ->from('GSMailerBundle:Mail','mail');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function  countDaily()
    {
        // Renvoie une liste contenant le nombre de mails envoyés chaque jours (début de la liste = aujourd'hui puis va à reculons)

        $qb = $this->_em->createQueryBuilder();
        // $qb->select('CAST(mail.sentAt AS DATE) as DateField')
        //     ->from('GSMailerBundle:Mail','mail')
        //     ->groupBy('CAST(mail.sentAt AS DATE)')
        // ;
        // $qb->select('COUNT(MONTH(mail.sentAt) AS sentMonth, DAY(user.birthDate) AS sentDay)')
        //     ->from('GSMailerBundle:Mail','mail')
        //     ->groupBy('sentMonth')
        //     ->addGroupBy('sentDay')
        // ;
        // $rsm = new ResultSetMapping();
        // $sql = 'SELECT COUNT(MONTH(mail.sentAt) AS sentMonth, DAY(user.birthDate) AS sentDay)'


        return $qb->getQuery()->getResult();

    }

    public function countByUser()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('u.firstName, u.lastName, count(mail.id)')
            ->from('GSMailerBundle:Mail','mail')
            ->leftJoin('mail.user', 'u')
            ->groupBy('u.id')
        ;

        return $qb->getQuery()->getResult();
    }
}
