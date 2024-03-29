<?php

namespace GS\MailBundle\Repository;

/**
 * MailRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MailRepository extends \Doctrine\ORM\EntityRepository
{
    public function getMailsToSend()
    {
        $qb = $this->createQueryBuilder('mail')
            ->where("mail.sentDate IS NULL")
            ->andWhere("mail.scheduledDate IS NOT NULL")
            ->andWhere("mail.scheduledDate <= :date")
            ->setParameter('date', new \DateTime("now", new \DateTimeZone("EUROPE/Paris")))
        ;

        return $qb
          ->getQuery()
          ->getResult()
        ;
    }
}
