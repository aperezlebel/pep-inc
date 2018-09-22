<?php

namespace GS\StatsBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * MailRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StatsService
{
    private $_em;
    private $start;
    private $end;

    public function __construct(EntityManager $em)
    {
        $this->_em = $em;
        $this->start = null;
        $this->end = null;
    }

    public function setStartDate($date)
    {
        $this->start = clone $date;
    }

    public function setEndDate($date)
    {
        $this->end = clone $date;
    }

    public function addTimeWindowConstraint($qb, $varToConstraint, $start = null, $end = null)
    {
        $newStart = $this->start;
        $newEnd = $this->end;

        if($start != null)
            $newStart = $start;
        if($end != null)
            $newEnd = $end;

        if($newStart == null && $newEnd == null)
            return $qb;
        if($newStart != null){
            $qb->andWhere($varToConstraint.' >= :start');
            $qb->setParameter('start', $newStart);
        }
        if($newEnd != null){
            $qb->andWhere($varToConstraint.' <= :end');
            $qb->setParameter('end', $newEnd);
        }
        return $qb;
    }

    public function nbSentProspeMails()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id)')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->where('mail.sentDate IS NOT NULL')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function nbScheduledProspeMails()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id)')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->where('mail.scheduledDate IS NOT NULL AND mail.sentDate IS NULL')
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function nbSentAndScheduledProspeMails()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id)')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->where('mail.scheduledDate IS NOT NULL AND mail.sentDate IS NOT NULL')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getDateDistributionProspeMail()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id), CAST(mail.sentDate AS DATE) AS sentDate')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            // ->addSelect('MONTH(mail.sentDate) AS sentDate')
            ->groupBy('sentDate')
            ->having('sentDate IS NOT NULL')
            // ->groupBy('CAST(mail.sentDate AS DATE)')
            // ->groupBy('datepart(MONTH, mail.sentDate)')
            // ->where('mail.scheduledDate IS NOT NULL AND mail.sentDate IS NOT NULL')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getResult();

    }

    public function getSpecDistributionProspeMail()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id), spec.name')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->leftJoin('prospeMail.specialization', 'spec')
            // ->where('spec IS NOT NULL')
            // ->addSelect('MONTH(mail.sentDate) AS sentDate')
            ->groupBy('spec.id')
            // ->groupBy('CAST(mail.sentDate AS DATE)')
            // ->groupBy('datepart(MONTH, mail.sentDate)')
            // ->where('mail.scheduledDate IS NOT NULL AND mail.sentDate IS NOT NULL')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getResult();
    }

    public function getStateDistributionProspeMail()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id), state.name')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->leftJoin('prospeMail.state', 'state')
            ->groupBy('state.id')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getResult();
    }

    public function getGenderDistributionProspeMail()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id), gender.name')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->leftJoin('prospeMail.gender', 'gender')
            ->groupBy('gender.id')
            // ->having('gender IS NOT NULL')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getResult();
    }

    public function getDailyDistributionProspeMail()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id), DAYOFWEEK(mail.sentDate) AS sentDay')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->groupBy('sentDay')
            ->having('sentDay IS NOT NULL')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        $daysIndex = array(
            1 => 'Dimanche',
            2 => 'Lundi',
            3 => 'Mardi',
            4 => 'Mercredi',
            5 => 'Jeudi',
            6 => 'Vendredi',
            7 => 'Samedi',
        );

        $temp = $qb->getQuery()->getResult();
        $result = array();
        $j = 1;
        for ($i=2; $i <= 7; $i++) {
            if($j < count($temp) && $temp[$j]['sentDay'] == $i){
                $result[$daysIndex[$i]] = $temp[$j][1];
                $j = $j + 1;
            }
            else
                $result[$daysIndex[$i]] = 0;
        }
        if($temp[0]['sentDay'] == 1){
            $result[$daysIndex[1]] = $temp[0][1];
        }
        return $result;
    }

    public function getHourlyDistributionProspeMail()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id), HOUR(mail.sentDate) AS sentHour')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->groupBy('sentHour')
            ->having('sentHour IS NOT NULL')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        $temp = $qb->getQuery()->getResult();
        $result = array();
        $j = 0;
        for ($i=0; $i < 24; $i++) {
            if($j < count($temp) && $temp[$j]['sentHour'] == $i){
                $result[$i] = $temp[$j][1];
                $j = $j + 1;
            }
            else
                $result[$i] = 0;
        }
        return $result;
        // Retourne un tableau indexé de 0 à 23 contenant pour chaque valeur le nombre de mails envoyés
    }

    public function getUserRankingProspeMail()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id) AS count, user.lastName')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            // ->from('GSUserBundle:User','user')
            ->leftJoin('prospeMail.mail', 'mail')
            ->leftJoin('prospeMail.user', 'user')
            ->groupBy('user.id')
            ->having('user IS NOT NULL')
            ->orderBy('count', 'DESC')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getResult();
    }

    public function getStateDistBySpecProspeMail()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('sspec.name AS specName, state.name AS stateName, count(prospeMail.id)')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            // ->from('GSMailerBundle:Specialization','sspec')
            // ->where('sspec.id = 4')
            ->leftJoin('prospeMail.mail', 'mail')
            ->leftJoin('prospeMail.state', 'state')
            ->leftJoin('prospeMail.specialization', 'sspec')
            ->groupBy('sspec.id')
            ->addGroupBy('state.id')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getResult();
    }

    public function getResponseDistByDay()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id), DAYOFWEEK(mail.sentDate) AS sentDay')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->leftJoin('prospeMail.state', 'state')
            ->where('state is NOT NULL')
            ->groupBy('sentDay')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        $daysIndex = array(
            1 => 'Dimanche',
            2 => 'Lundi',
            3 => 'Mardi',
            4 => 'Mercredi',
            5 => 'Jeudi',
            6 => 'Vendredi',
            7 => 'Samedi',
        );

        $temp = $qb->getQuery()->getResult();
        $result = array();
        $j = 1;
        for ($i=2; $i <= 7; $i++) {
            if($j < count($temp) && $temp[$j]['sentDay'] == $i){
                $result[$daysIndex[$i]] = $temp[$j][1];
                $j = $j + 1;
            }
            else
                $result[$daysIndex[$i]] = 0;
        }
        if($temp[0]['sentDay'] == 1){
            $result[$daysIndex[1]] = $temp[0][1];
        }
        return $result;
    }

    public function getResponseDistByHour()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id), HOUR(mail.sentDate) AS sentHour')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->leftJoin('prospeMail.state', 'state')
            ->where('state is NOT NULL')
            ->groupBy('sentHour')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        $temp = $qb->getQuery()->getResult();
        $result = array();
        $j = 0;
        for ($i=0; $i < 24; $i++) {
            if($j < count($temp) && $temp[$j]['sentHour'] == $i){
                $result[$i] = $temp[$j][1];
                $j = $j + 1;
            }
            else
                $result[$i] = 0;
        }
        return $result;
    }

    public function getResponseDistByGender()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('count(prospeMail.id), gender.name')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->leftJoin('prospeMail.state', 'state')
            ->leftJoin('prospeMail.gender', 'gender')
            ->where('state is NOT NULL')
            ->groupBy('gender.id')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getResult();
    }

    public function getStateDistByGenderProspeMail()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('gender.name AS genderName, state.name AS stateName, count(prospeMail.id)')
            ->from('GSMailerBundle:ProspeMail','prospeMail')
            ->leftJoin('prospeMail.mail', 'mail')
            ->leftJoin('prospeMail.state', 'state')
            ->leftJoin('prospeMail.gender', 'gender')
            ->groupBy('gender.id')
            ->addGroupBy('state.id')
        ;

        $qb = $this->addTimeWindowConstraint($qb, 'mail.sentDate');

        return $qb->getQuery()->getResult();
    }

}