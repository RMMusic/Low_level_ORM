<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 08.02.2016
 * Time: 18:41
 */
'SELECT name FROM user WHERE id = 4';
'SELECT name FROM user WHERE name = "Taras" AND age = 26';
'SELECT name FROM user WHERE (name = "Taras") AND (age > 26 AND age < 36 and age is not null)';

$first = array(
    "id" => 4,
);

$second = array(
    "name" => "Taras",
    "age" => 26,
);

$third = array(
    "name" => "Taras",
    "age" => array(
        ">" => "26",
        "<" => "36",
        "null" => false,
    ),
);

// (=, >, <, >=, <=, !=, in, not in, null)

function where(array $arrayWhere)
{
    $conditionData = array();

    foreach ($arrayWhere as $column => $condition) {
        $columnWhereData = array();

        if (!is_array($condition)) {
            $condition = array("=" => $condition);
        }

        foreach ($condition as $operation => $value) {
            $columnWhereData[] = getMappedCondition($column, $operation, $value);
        }

        $conditionData[] = implode(' AND ', $columnWhereData);
    }

    $conditionString = "WHERE " . (count($conditionData) ? " (" . implode(') AND (', $conditionData) . ")" : '1');

    return $conditionString;
}

function getMappedCondition($column, $operation, $value)
{

    $mapping = array(
        "=" => "$column = '$value'",
        ">" => "$column > '$value'",
        "<" => "$column < '$value'",
        ">=" => "$column >= '$value'",
        "<=" => "$column <= '$value'",
        "!=" => "$column <> '$value'",
        "in" => $column . ( is_array($value) ? " IN ('" . implode("','", $value) . "')" : " = '$value'" ),
        "not in" => $column . ( is_array($value) ? " NOT IN ('" . implode("','", $value) . "')" : " = '$value'" ),
        "null" => $column . ($value == true ? " IS NULL" : " IS NOT NULL"),
    );

    return array_key_exists($operation, $mapping) ? $mapping[$operation] : "";
}

echo "SELECT name FROM user " . where($third) . ";";