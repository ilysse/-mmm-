<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../init.php';


header('Content-Type: application/json');

$user = new User($pdo);

try {
    $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
    $columnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
    $columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'id';
    $columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
    $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

    $totalRecords = $user->getTotalRecords('');
    $totalRecordwithFilter = $user->getTotalRecords($searchValue);
    $userRecords = $user->getUsers($searchValue, $columnName, $columnSortOrder, $start, $length);

    $data = array();
    foreach ($userRecords as $row) {
        $data[] = array(
            "id" => $row['id'],
            "username" => $row['username'],
            "user_role" => $row['user_role'],
            "added_date" => date('Y-m-d H:i:s', strtotime($row['added_date'])),
            "action" => '
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="index.php?page=edit_user&edit_id=' . $row['id'] . '" class="btn btn-secondary btn-sm rounded-0" type="button"><i class="fas fa-edit"></i></a>
                   <a href="javascript:void(0);" class="btn btn-danger btn-sm rounded-0" id="user_delete_btn" data-id="' . $row['id'] .'" type="button"><i class="fas fa-trash-alt"></i></a>
                </div>
            ',
        );
    }

    $response = array(
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalRecordwithFilter,
        "data" => $data
    );

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(array("error" => $e->getMessage()));
}