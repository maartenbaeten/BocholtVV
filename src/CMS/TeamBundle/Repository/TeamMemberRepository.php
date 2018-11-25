<?php

namespace CMS\TeamBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TeamMemberRepository extends EntityRepository
{
    public function getNumberOfTeamMembersPerPaidMembership() {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = '
                SELECT COUNT(team_member.id) as quantity, team_member.membershipPaid AS name
                FROM team_member
                GROUP BY  team_member.membershipPaid;
               ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }
}
