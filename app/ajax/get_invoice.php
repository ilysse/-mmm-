<?php
header('Content-Type: application/json');
include '../init.php'; // Adjust the path to your init.php file

// Retrieve the IDs from the POST request
$ids = isset($_POST['ids']) ? $_POST['ids'] : [];

if (empty($ids)) {
    echo json_encode(['success' => false, 'message' => 'No IDs provided']);
    exit;
}

$data = [];

foreach ($ids as $id) {
    // Fetch the main invoice details
    $sell_total = $obj->find('invoice', 'id', $id);

    if ($sell_total) {
        // Fetch customer details
        $customer = $obj->find('member', 'id', $sell_total->customer_id);

        // Fetch products in the invoice
        $all_product = $obj->findWhere('invoice_details', 'invoice_no', $sell_total->id);
        $products = [];

        foreach ($all_product as $products_item) {
            $pid = $products_item->pid;
            $p_brand = $obj->find('products', 'id', $pid);
            
            $products[] = [
                'product_name' => $products_item->product_name,
                'brand_name' => $p_brand->brand_name,
                'quantity' => $products_item->quantity,
                'price' => $p_brand->sell_price,
                'total' => number_format($products_item->price, 2)
            ];
        }

        $data[] = [
            'sell_total' => [
                'order_date' => $sell_total->order_date,
                'invoice_number' => $sell_total->invoice_number,
                'seller_id' => $sell_total->seller_id,
                'net_total' => number_format($sell_total->sub_total, 2)
            ],
            'customer' => [
                'name' => $customer->name,
                'company' => $customer->company,
                'address' => $customer->address,
                'con_num' => $customer->con_num,
                'email' => $customer->email,
                'member_id' => $customer->member_id
            ],
            'products' => $products
        ];
    }
}

echo json_encode(['success' => true, 'data' => $data]);
