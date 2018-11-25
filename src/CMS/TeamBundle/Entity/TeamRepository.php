<?php

namespace CMS\TeamBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    public function findLatestGame($teamId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM CMSTeamBundle:Calendar c JOIN c.team t WHERE c.date < :date AND t.id = :teamid ORDER BY c.date DESC'
            )
            ->setParameter('date', new \DateTime())
            ->setParameter('teamid', $teamId)
            ->getResult();
    }

    public function getNumberOfMembersPerTeam() {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = '
            SELECT COUNT(team_member.id) as quantity, team.teamName as name
            FROM team, team_member
            WHERE team_member.team_id = team.id
            GROUP BY  team.teamName
            ORDER BY quantity DESC;
               ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getNumberOfNewsPerTeam() {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = '
                SELECT COUNT(news.id) as quantity, team.teamName as name
                FROM team, news
                WHERE news.team_id = team.id
                GROUP BY  team.teamName
                ORDER BY quantity DESC;
               ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }
}