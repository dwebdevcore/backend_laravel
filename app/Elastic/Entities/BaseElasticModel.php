<?php
/**
 * Created by PhpStorm.
 * User: byabuzyak
 * Date: 11/21/18
 * Time: 12:42 PM
 */

namespace App\Elastic\Entities;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Elasticsearch\ClientBuilder;
use ScoutElastic\Facades\ElasticClient;

abstract class BaseElasticModel
{
    /**
     * @var ElasticClient
     */
    private $client;

    /**
     * @var string
     */
    protected $index = '';
    /**
     * @var string
     */
    protected $indexType = '';
    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var int
     */
    protected $size = 10;
    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var array
     */
    protected $columns = [
        'id',
    ];

    /**
     * @var array
     */
    private $config = [
        'body' => []
    ];

    /**
     * @var null
     */
    public $hitName = null;

    /**
     * Business constructor.
     */
    public function __construct()
    {
        $hosts = [
            env('SCOUT_ELASTIC_HOST', 'localhost:9000')
        ];

        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
        $this->page   = Request::get('page', 1);
    }

    /**
     * @param array $query
     * @return $this
     */
    public function query(array $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->size = $limit;

        return $this;
    }

    private function build()
    {
        $this->config['index'] = $this->index;
        $this->config['type']  = $this->indexType;

        if (!empty($this->query)) {
            $this->config['body']['query'] = $this->query;
        }
    }

    /**
     * @return array
     */
    private function search(): array
    {
        $this->build();
        return $this->client->search($this->config);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function _getSource(array $data): array
    {
        if (!empty($this->columns)) {
            return array_only($data['_source'], $this->columns);
        }

        return $data['_source'];
    }

    /**
     * @param $data
     * @return array
     */
    public function getHitsSource($data) {
        return array_map([$this, '_getSource'], $data['inner_hits'][$this->hitName]['hits']['hits']);
    }

    /**
     * @param array $columns
     * @return BaseElasticModel
     */
    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setHitSource(string $name) {
        $this->hitName = $name;

        return $this;
    }

    /**
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(?int $perPage = null)
    {
        $this->config['from'] = ($this->page - 1) * $this->size;
        $this->config['size'] = $perPage ?? $this->size;

        $search   = $this->search();
        $totalCnt = $search['hits']['total'];
        $results  = array_map([$this, $this->hitName ? 'getHitsSource' : '_getSource'], $search['hits']['hits']);

        if ($this->hitName) {
            $results  = array_flatten($results, 1);
        }

        return
            new LengthAwarePaginator(
                $results,
                $totalCnt,
                $this->size,
                $this->page,
                ['path' => url(Request::route()->uri)]
            );
    }

    /**
     * @param array $rule
     * @return BaseElasticModel
     */
    public function rule(array $rule)
    {
        $this->config = $rule;

        return $this;
    }

    /**
     * @return array
     */
    public function all() {
        return $this->search();
    }
}