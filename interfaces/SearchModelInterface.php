<?php 

namespace crud\interfaces;

use yii\data\DataProviderInterface;

interface SearchModelInterface
{

    public function search(array $params): DataProviderInterface;

}
