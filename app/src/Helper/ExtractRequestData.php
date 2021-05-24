<?php


namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class ExtractRequestData
{
    private function getRequestData(Request $request)
    {
        $queryString = $request->query->all();
        $order = $request->query->get('sort');
        unset($queryString['sort']);

        $currentPage = array_key_exists('page', $queryString)
            ? $queryString['page']
            : 1;
        unset($queryString['page']);

        $perPage = array_key_exists('per_page', $queryString)
            ? $queryString['per_page']
            : 5;
        unset($queryString['per_page']);

        return [$queryString, $order, $currentPage, $perPage];
    }

    public function getOrder(Request $request)
    {
        [, $order] = $this->getRequestData($request);
        return $order;
    }

    public function getFilter(Request $request) {
        [$filter, ] = $this->getRequestData($request);
        return $filter;
    }

    public function getPaginationData(Request $request)
    {
        [, , $currentPage, $perPage] = $this->getRequestData($request);
        return [$currentPage, $perPage];
    }

}