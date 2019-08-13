<?php

/*
 * This file is part of the kalamu/default-bootstrap-template-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\DefaultBootstrapTemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FragmentController extends Controller
{

    public function lastPostsAction(Request $Request, string $content_type, int $max)
    {
        $manager = $this->get('kalamu_cms_core.content_type.manager')->getType($content_type);
        $queryBuilder = $manager->getQueryBuilderForIndex($Request);
        $paginator = $this->get('knp_paginator')->paginate($queryBuilder, 1, $max);

        $response = $this->render('@KalamuDefaultBootstrapTemplate/_fragments/last_posts.html.twig',
            ['posts' => $paginator]);

        $response->setSharedMaxAge(600);
        return $response;
    }

    public function taxonomiesAction(Request $Request, string $content_type, int $max)
    {
        $manager = $this->get('kalamu_cms_core.content_type.manager')->getType($content_type);
        $queryBuilder = $manager->getQueryBuilderForIndex($Request);
        $result = $queryBuilder->leftJoin('c.terms', 'term')
            ->leftJoin('term.taxonomy', 't')
            ->select('t.libelle taxonomy, term.id term_id, term.libelle libelle, term.slug, count(c.id) nb_posts')
            ->groupBy('t.libelle, term.id, term.slug, term.libelle')
            ->andWhere('t.libelle IS NOT NULL')
            ->orderBy('t.libelle, count(c.id)', 'DESC')
            ->getQuery()->getArrayResult();

        $taxonomies = [];

        foreach($result as $row){
            if(!array_key_exists($row['taxonomy'], $taxonomies)){
                $taxonomies[$row['taxonomy']] = [];
            }

            if(count($taxonomies[$row['taxonomy']]) < $max){
                $taxonomies[$row['taxonomy']][] = $row;
            }
        }

        $response = $this->render('@KalamuDefaultBootstrapTemplate/_fragments/taxonomies.html.twig',
            ['taxonomies' => $taxonomies]);

        $response->setSharedMaxAge(600);
        return $response;
    }

}