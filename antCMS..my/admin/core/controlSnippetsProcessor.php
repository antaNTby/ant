<?php

// require_once '/admin/core/classes/class.Toasts.php'; //load php class
// $phpToast      = new Toasts();

$exampleData = [
    "primaryID"  => 11,
    "configName" => PRODUCTS_TABLE,
    "fieldName"  => 'name',
    "newValue"   => "мыло",
    "oldValue"   => "шило",
    "rowNumber"  => 9,
];

if (isset($_GET['app']) && isset($_GET['operation']) && ($_GET['app'] == 'controlSnippetsProcessor'))
{
    $errors    = [];
    $operation = $_GET['operation'] ?? 'missedOperation';

    if (($operation == 'updateCSValue'))
    {

        $requestData = json_decode(file_get_contents("php://input"), true, JSON_BIGINT_AS_STRING | JSON_INVALID_UTF8_IGNORE);

        $result = false;

        if ($requestData["newValue"] == $requestData["oldValue"])
        {
            $errors[] = "Значение не менялось";
        }

        if (!isset($requestData["primaryID"]))
        {
            $errors[] = "Нет первичного ключа";
        }

        foreach ($exampleData as $key => $value)
        {
            if (!isset($requestData[$key]))
            {
                $errors[] = "Нет `{$key}` ";
            }
        }

        if ($errors === [])
        {
            $errors = false;

            $data = [
                $requestData["fieldName"] => $requestData["newValue"],
            ];

            $db->table(ANT_RBCOLUMNS)->where('id', $requestData["primaryID"])->update($data);

        }
        header('Content-Type: application/json; charset=utf-8');
        exit(json_encode(
            [

                'errors'      => $errors,
                'message'     => 'Усё нармальна мама , трын-дын-дым, дын-дын-дым!',
                'requestData' => $requestData,
                'result'      => $result,
                'numRows'     => $db->numRows(),
                'getQuery'    => $db->getQuery(),
                'queryCount'  => $db->queryCount(),

            ]

        ));
    }

    if (($operation == 'updateCSValue'))
    {

    }

}