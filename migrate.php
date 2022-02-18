<?php 

$db = Db::getInstance();
$ps_message = $db->executeS("SELECT * FROM ps_message");
foreach($ps_message as $message)
{
    $customer = new Customer((int)$message['id_customer']);
    // dump($customer);
    $id_order = (int)$message['id_order'];
    // check if tread already exist for this order
    $thread = $db->getRow('SELECT * FROM ps_customer_thread WHERE id_order = '.$id_order);
    if(!$thread){
        // creating thread
        $token =  Tools::passwdGen(8,'NO_NUMERIC');
        $customer_id = $customer->id;
        $status = 'closed';
        $email = $customer->email;
        $date_add = date('Y-m-d 00:00:00');
        $date_upd = date('Y-m-d 00:00:00');
        $id_lang = 1;
        $id_shop = 1;
        $id_contact = 0;
        $threadObj = new CustomerThread;
        $threadObj->token = $token;
        $threadObj->id_order = $id_order;
        $threadObj->id_customer = $customer_id;
        $threadObj->status = $status;
        $threadObj->email = $email;
        $threadObj->date_add = $date_add;
        $threadObj->date_upd = $date_upd;
        $threadObj->id_lang = $id_lang;
        $threadObj->id_shop = $id_shop;
        $threadObj->id_contact = $id_contact;
        $threadObj->save();
    }
    $thread = $db->getRow('SELECT * FROM ps_customer_thread WHERE id_order = '.$id_order);
    // insert in existing thread
    $id_customer_thread = (int)$thread['id_customer_thread'];
    $private = '1';
    $read = '1';
    $message = pSQL($message['message']);
    $id_employee = '1';
    $sql = "INSERT INTO ps_customer_message SET ";
    $sql .= "`id_customer_thread` = $id_customer_thread, ";
    $sql .= "`id_employee` = $id_employee, ";
    $sql .= "`message` = '".$message."', ";
    $sql .= "`read` = $read, ";
    $sql .= "`private` = $private, ";
    $sql .= "`date_add` = '".date('Y-m-d 00:00:00')."', ";
    $sql .= "`date_upd` = '".date('Y-m-d 00:00:00')."' ";
    $db->execute($sql);
}