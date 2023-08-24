<pre>
<?
require 'config.php';
require 'functions.php';

// получаем номера складов по id
$warehouseIds = ['3462651', '3462656'];
$warehouseNumbers = [];

foreach ($warehouseIds as $warehouseId) {
    $warehouseNumbers[] = getWarehouseNumber($warehouseId, $domain, $headers);
}

// обновляем остатки (сейчас рандомные)
$productIds = array(379736701, 376009484);
$updateData = array("variants" => array());

foreach ($productIds as $productId) {
    $productData = getResponse("$domain/admin/products/$productId.json", $headers, 'get', '');

    if ($productData === false) {
        echo "Ошибка при выполнении запроса для продукта.";
    } else {
        $productData = json_decode($productData, true);
        $variantId = $productData['variants'][0]['id'];

        $variantUpdateData = array("id" => $variantId);

        foreach ($warehouseNumbers as $warehouseNumber) {
            $rand_number = mt_rand(1, 10); // тут будут значения остатков
            $variantUpdateData["quantity_at_warehouse{$warehouseNumber}"] = $rand_number;
        }

        $updateData["variants"][] = $variantUpdateData;
    }
}

print_r($updateData);
echo "\n\n";

$updateResponse = getResponse("$domain/admin/products/variants_group_update.json", $headers, 'put', $updateData);

if ($updateResponse === false) {
    echo "Ошибка при выполнении запроса для обновления остатков.";
} else {
    $responseData = json_decode($updateResponse, true);
    print_r($responseData);
}
?>
</pre>