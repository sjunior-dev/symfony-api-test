<?php

declare(strict_types=1);

namespace App\CoreAPI\Helper;

use App\CoreAPI\DTO\RequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestHelper
{
    public function __construct(
    ) {
    }

    public function parse(Request $request): RequestDTO
    {
        return new RequestDTO(
            data: $this->decodeJson($request),
            filters: $this->parseFilters($request),
            sort: $this->parseSorting($request),
            start: $this->getStart($request),
            size: $this->getSize($request)
        );
    }
    public function getStart(Request $request): int
    {
        return (int) $request->query->get('start', 0);
    }

    public function getSize(Request $request): int
    {
        return (int) $request->query->get('size', 10);
    }

    /** @return array<int, mixed> */
    public function parseFilters(Request $request): array
    {
        $query = $request->query->all();
        unset($query['sort'], $query['order'], $query['start'], $query['size']);

        $filters = [];
        foreach ($query as $key => $value) {
            if (strpos($key, '&') !== false) {
                $conditions = explode('&', $key);
                foreach ($conditions as $condition) {
                    $this->parseCondition($filters, $condition, $value);
                }
            } elseif (strpos($key, '|') !== false) {
                $conditions = explode('|', $key);
                $orConditions = [];
                foreach ($conditions as $condition) {
                    $orFilters = [];
                    $this->parseCondition($orFilters, $condition, $value);
                    $orConditions[] = $orFilters;
                }
                $filters[] = ['or' => $orConditions];
            } else {
                $this->parseCondition($filters, $key, $value);
            }
        }

        return $filters;
    }

    /** @param array<int, mixed> &$filters */
    private function parseCondition(array &$filters, string $key, string $value): void
    {
        if (preg_match('/(.*?)(!=|<=|>=|=|<|>)(.*)/', $key, $matches)) {
            [$full, $field, $operator, $val] = $matches;
            $filters[] = ['field' => $field, 'operator' => $operator, 'value' => $val];
        } else {
            $filters[] = ['field' => $key, 'operator' => '=', 'value' => $value];
        }
    }

    /** @return array<string, mixed> */
    public function parseSorting(Request $request): array
    {
        $sort = (string) $request->query->get('sort', 'id');
        $order = $request->query->get('order', 'ASC');
        $sorts = explode(',', $sort);
        $sortArray = [];
        foreach ($sorts as $field) {
            $direction = $order;
            if (strpos($field, '-') === 0) {
                $field = substr($field, 1);
                $direction = 'DESC';
            }
            $sortArray[$field] = $direction;
        }

        return $sortArray;
    }

    /** @return array<int, mixed> */
    public function parseFields(Request $request): array
    {
        $fields = (string) $request->query->get('fields', '');

        return ($fields !== null && $fields !== '') ? explode(',', $fields) : [];
    }

    /** @return array<int, mixed> */
    public function parseExclude(Request $request): array
    {
        $exclude = (string) $request->query->get('exclude', '');

        return ($exclude !== null && $exclude !== '') ? explode(',', $exclude) : [];
    }

    protected function decodeJson(Request $request): mixed
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Invalid JSON data');
        }

        return $data;
    }
}
