<?php

/**
 * Order model
 * @package EMLOG
 * @link https://www.emlog.net
 */

class Order_Model
{

    private $db;
    private $table;
    private $app_name;

    function __construct($app_name)
    {
        $this->app_name = $app_name;
        $this->db = Database::getInstance();
        $this->table = DB_PREFIX . 'order';
    }

    function generateOrderNumber()
    {
        // 当前时间戳（精确到毫秒）
        $microtime = microtime(true);
        // 转换为毫秒后移除小数点
        $timestamp = str_replace('.', '', $microtime);
        $randomNumber = mt_rand(100000, 999999);
        $orderNumber = $timestamp . $randomNumber;
        return $orderNumber;
    }


    function getOrderById($orderId)
    {
        $orderId = addslashes($orderId);
        $sql = sprintf(
            "SELECT * FROM `%s` WHERE order_id='%s'",
            $this->table,
            $orderId
        );
        $order = $this->db->once_fetch_array($sql);
        return $order ? $this->formatOrder($order) : null;
    }

    function getOrdersByUserId($userId, $limit = 10, $offset = 0, $isPaid = false)
    {
        $userId = (int)$userId;
        $where = "WHERE order_uid=$userId AND app_name='$this->app_name'";
        if ($isPaid) {
            $where .= " AND pay_price > 0";
        }
        $sql = sprintf(
            "SELECT * FROM `%s` %s ORDER BY create_time DESC LIMIT %d OFFSET %d",
            $this->table,
            $where,
            (int)$limit,
            (int)$offset
        );
        $result = $this->db->query($sql);
        $orders = [];
        while ($row = $this->db->fetch_array($result)) {
            $orders[] = $this->formatOrder($row);
        }
        return $orders;
    }

    function createOrder($uid, $pay_type, $sku_name, $sku_id, $price)
    {
        $order_id = $this->generateOrderNumber();
        $data = [
            'app_name' => $this->app_name,
            'order_id' => $order_id,
            'order_uid' => $uid,
            'pay_type' => $pay_type,
            'sku_name' => $sku_name,
            'sku_id' => $sku_id,
            'price' => $price,
            'update_time' => time(),
            'create_time' => time()
        ];
        $fields = implode(',', array_keys($data));
        $values = "'" . implode("','", array_map('addslashes', $data)) . "'";
        $sql = sprintf(
            "INSERT INTO `%s` (%s) VALUES (%s)",
            $this->table,
            $fields,
            $values
        );
        $this->db->query($sql);
        return $order_id;
    }

    function updateOrder($orderId, $data)
    {
        $orderId = addslashes($orderId);
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = sprintf("%s='%s'", $key, addslashes($value));
        }
        $updates[] = "update_time=" . time();
        $sql = sprintf(
            "UPDATE `%s` SET %s WHERE order_id='%s'",
            $this->table,
            implode(',', $updates),
            $orderId
        );
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function isOrderPaid($orderId)
    {
        $order = $this->getOrderById($orderId);
        return $order && $order['pay_price'] >= $order['price'];
    }

    function hasPurchasedSku($userId, $skuId, $skuName = '')
    {
        $userId = (int)$userId;
        $skuId = (int)$skuId;

        $sql = sprintf(
            "SELECT COUNT(*) AS total FROM `%s` WHERE order_uid=%d AND sku_id=%d AND pay_price >= price",
            $this->table,
            $userId,
            $skuId
        );

        if (!empty($skuName)) {
            $skuName = addslashes($skuName);
            $sql .= sprintf(" AND sku_name='%s'", $skuName);
        }

        $result = $this->db->once_fetch_array($sql);
        return (int)$result['total'] > 0;
    }

    private function formatOrder($order)
    {
        $order['id'] = (int)$order['id'];
        $order['order_uid'] = (int)$order['order_uid'];
        $order['sku_id'] = (int)$order['sku_id'];
        $order['price'] = (float)$order['price'];
        $order['pay_price'] = (float)$order['pay_price'];
        $order['refund_amount'] = (float)$order['refund_amount'];
        $order['update_time'] = date('Y-m-d H:i:s', $order['update_time']);
        $order['create_time'] = date('Y-m-d H:i:s', $order['create_time']);
        return $order;
    }
}
