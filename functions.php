<?
function getResponse($url, $headers, $method, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method == "put") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}

function getWarehouseNumber($warehouseId, $domain, $headers)
{
    $array_index = -1;
    $response = getResponse("$domain/admin/warehouses/$warehouseId.json", $headers, 'get', '');

    if ($response === false) {
        echo "Ошибка при получении номеров складов";
    } else {
        $data = json_decode($response, true);
        $array_index = $data['array_index'];
    }

    return $array_index;
}
