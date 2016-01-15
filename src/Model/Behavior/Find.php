<?php

namespace App\Model\Behavior;

use Doctrine\ORM\Query;

trait Find
{
    public function findOneBy(array $filter = [])
    {
        $result = $this->findBy($filter);

        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    public function findBy(array $filter = [])
    {
        $alias = 'c';
        $qb = $this->app['em']->getRepository('App\Entity\Client')
                              ->createQueryBuilder($alias)
                              ->select($alias);

        foreach ($filter as $key => $v) {
            $qb->andWhere($alias . '.' . $key . ' = :' . $alias . $key)
               ->setParameter(':' . $alias . $key, $v);
        }

        $result = $qb->getQuery()
                     ->setHint(Query::HINT_INCLUDE_META_COLUMNS, true)
                     ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
