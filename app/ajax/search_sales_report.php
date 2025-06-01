<?php 
require_once '../init.php';

if (isset($_POST) && !empty($_POST)) {
    $issueData = $_POST['issuedate'];
    $customer = $_POST['customer'];
    $sellerId = $_POST['seller_id']; // Get the seller ID

    $data = explode('-', $issueData);
    $issu_first_date = $obj->convertDateMysql(trim($data[0]));
    $issu_end_date = $obj->convertDateMysql(trim($data[1]));

    // Start building the SQL query
    $query = "SELECT * FROM `invoice` WHERE `order_date` BETWEEN :start_date AND :end_date";
    $params = [
        ':start_date' => $issu_first_date,
        ':end_date' => $issu_end_date,
    ];

    // Check for customer filtering
    if ($customer != 'all') {
        $query .= " AND `customer_id` = :customer_id";
        $params[':customer_id'] = $customer;
    }

    // Check for seller ID filtering
    if (!empty($sellerId)) {
        $query .= " AND `seller_id` = :seller_id";
        $params[':seller_id'] = $sellerId;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($res) {
        $i = 0;
        foreach ($res as $data) {
            $i++;
            ?>
            <tr onclick="redirectToInvoice(this)" style="cursor: pointer;">
                <td><?=$i;?></td>
                <td hidden><?=$data->id?></td>
                <td><?=$data->invoice_number;?></td>
                <td><?=$data->order_date;?></td>
                <td><?=$data->customer_id;?></td>
                <td><?=$data->customer_name;?></td>
                <td><?=$data->net_total;?></td>
                <td><?=$data->paid_amount;?></td>
                <td><?=$data->due_amount;?></td>
            </tr>
            <?php 
        }
        ?>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total:</th>
            <th>
                <?php  
                $stmt = $pdo->prepare("SELECT SUM(`net_total`) FROM `invoice` WHERE `order_date` BETWEEN :start_date AND :end_date" . 
                    ($customer != 'all' ? " AND `customer_id` = :customer_id" : "") . 
                    (!empty($sellerId) ? " AND `seller_id` = :seller_id" : ""));
                $stmt->execute(array_merge($params, [
                    ':start_date' => $issu_first_date,
                    ':end_date' => $issu_end_date,
                ]));
                $res = $stmt->fetch(PDO::FETCH_NUM);
                echo $res[0];
                ?>
            </th>
            <th>
                <?php  
                $stmt = $pdo->prepare("SELECT SUM(`paid_amount`) FROM `invoice` WHERE `order_date` BETWEEN :start_date AND :end_date" . 
                    ($customer != 'all' ? " AND `customer_id` = :customer_id" : "") . 
                    (!empty($sellerId) ? " AND `seller_id` = :seller_id" : ""));
                $stmt->execute(array_merge($params, [
                    ':start_date' => $issu_first_date,
                    ':end_date' => $issu_end_date,
                ]));
                $res = $stmt->fetch(PDO::FETCH_NUM);
                echo $res[0];
                ?>
            </th>
            <th>
                <?php  
                $stmt = $pdo->prepare("SELECT SUM(`due_amount`) FROM `invoice` WHERE `order_date` BETWEEN :start_date AND :end_date" . 
                    ($customer != 'all' ? " AND `customer_id` = :customer_id" : "") . 
                    (!empty($sellerId) ? " AND `seller_id` = :seller_id" : ""));
                $stmt->execute(array_merge($params, [
                    ':start_date' => $issu_first_date,
                    ':end_date' => $issu_end_date,
                ]));
                $res = $stmt->fetch(PDO::FETCH_NUM);
                echo $res[0];
                ?>
            </th>
        </tr>
        <?php 
    } else {
        echo "<p class='pt-1' style='text-align:center;'>No data found</p>"; 
    }
}
?>
