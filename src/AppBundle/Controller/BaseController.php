<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Exception\ApiException;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends FOSRestController
{
    /**
     * Default item limit for pagination
     *
     * @var int
     */
    protected $defaultLimit = 20;

    /**
     * @return Account
     */
    protected function getAccount()
    {
        return $this->getUser();
    }


    /**
     * Throw an api exception
     *
     * @param string|\Exception $message
     * @param int|mixed $codeOrExtra
     * @param null|mixed $extra
     * @throws \Exception
     */
    protected function createApiException($message = 'An error occurred.', $codeOrExtra = 200, $extra = null)
    {
        if ($message instanceof \Exception) {
            throw $message;
        }

        $code = 200;

        if (! is_int($codeOrExtra)) {
            $extra = $codeOrExtra;
        } else {
            $code = $codeOrExtra;
        }

        throw new ApiException($message, $code, $extra);
    }

    /**
     * Calculate pagination offset and limit
     *
     * @param Request $request
     * @param string $pageQueryName
     * @param string $limitQueryName
     * @return array
     */
    protected function calculatePagination(Request $request, $pageQueryName = 'page', $limitQueryName = 'limit')
    {
        $page = $request->query->get($pageQueryName, 1);
        $limit = $request->query->get($limitQueryName, $this->defaultLimit);

        $page = (int) $page === 0 ? 1 : $page;
        $offset = ($page - 1) * $limit;

        return [
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    /**
     * Calculate order
     *
     * @param Request $request
     * @param $columns
     * @param string $queryName
     * @return array|null
     */
    protected function calculateOrder(Request $request, $columns, $queryName = 'order')
    {
        $sort = $request->query->get($queryName, null);

        if (empty($sort)) {
            return null;
        }

        if (! is_array($columns)) {
            $columns = [$columns];
        }

        $order = stripos($sort, '-') === 0 ? 'DESC' : 'ASC';
        $sort = ltrim($sort, '-');

        if (! in_array($sort, $columns)) {
            return null;
        }

        return [$sort => $order];
    }

}