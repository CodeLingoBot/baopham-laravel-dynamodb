<?php

namespace BaoPham\DynamoDb;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class ModelObserver.
 */
class ModelObserver
{
    /**
     * @var \Aws\DynamoDb\DynamoDbClient
     */
    protected $dynamoDbClient;

    /**
     * @var \Aws\DynamoDb\Marshaler
     */
    protected $marshaler;

    /**
     * @var \BaoPham\DynamoDb\EmptyAttributeFilter
     */
    protected $attributeFilter;

    public function __construct(DynamoDbClientInterface $dynamoDb)
    {
        $this->dynamoDbClient = $dynamoDb->getClient();
        $this->marshaler = $dynamoDb->getMarshaler();
        $this->attributeFilter = $dynamoDb->getAttributeFilter();
    }

    

    

    public function created($model)
    {
        $this->saveToDynamoDb($model);
    }

    public function updated($model)
    {
        $this->saveToDynamoDb($model);
    }

    public function deleted($model)
    {
        $this->deleteFromDynamoDb($model);
    }

    public function restored($model)
    {
        $this->saveToDynamoDb($model);
    }
}
