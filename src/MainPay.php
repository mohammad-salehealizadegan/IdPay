<?php

namespace Mdafzaran\Idpay;

use MdAfzaran\IdPay\IdPayRequest as IdPayReq;
use MdAfzaran\IdPay\MdIdPayInfo as PayInfo;

define('PENDING', 1);
define('RETURNED', 2);
define('FAILED', 3);
define('SUCCESS', 100);

class MainPay
{
    private $apiKey;
    private $paymentPath;
    private $verifyPath;
    private $service;
    private $sandBox;
    private $status;
    private $track_id;
    private $id;
    private $card_no;
    private $hashed_card_no;
    private $date;
    private $order_id;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->paymentPath = 'https://api.idpay.ir/v1.1/payment';
        $this->verifyPath = 'https://api.idpay.ir/v1.1/payment/verify';
        $this->service = new IdPayReq();
    }

    /**
     * Create new payment on Idpay
     * Get payment path and payment id.
     *
     * @param string $callback
     * @param string $amount
     * @param string $name
     * @param string $phone
     * @param string $description
     *
     * @return array|@paymentPath
     */

    public function checkUniqueIdPayIds($id)
    {
        if (!$id && !is_null($id)) {
            return false;
        }

        $payinfo = PayInfo::where('pay_id',$id)->first();

        if ($payinfo) {
            return false;
        }

        PayInfo::create([
            "pay_id" => $id
        ]);

        return true;
    }
    public function preparePay($callback, $orderId, $amount,$sandBox = false,$ssl_verify=false ,$name = null, $phone = null, $description = null)
    {

        $this->sandBox = $sandBox;
        
        $headers = [
            'type' => 'application/json',
            'apiKey' => $this->apiKey,
            'sandBox' => $this->sandBox
        ];

        $data = [
            'amount'    => $amount,
            'order_id'  => $orderId,
            'callback'  => $callback,
            'name' => $name,
            'phone' => $phone,
            'description' => $description
        ];

        if (!is_null($name) && !empty($name)) {
            $data['name'] = $name;
        }
        if (!is_null($phone) && !empty($phone)) {
            $data['phone'] = $phone;
        }
        if (!is_null($description) && !empty($description)) {
            $data['desc'] = $description;
        }

        $response = $this->service->sendRequest($this->paymentPath, $data, $headers,$ssl_verify);

        $result = json_decode($response); 
        
        if (isset($result->id)) {
            if (isset($result->link)) {
                if ($this->checkUniqueIdPayIds($result->id)) {
                    return $result->link;
                }
            }
        }
        else
        {
            dd($result);
        }

        return false;
    }

    public function payVerify($id,$order_id)
    {
        $headers = [
            'type' => 'application/json',
            'apiKey' => $this->apiKey,
            'sandBox' => $this->sandBox
        ];

        if ($id && $order_id) {
            $data = [
                'id'    => $id,
                'order_id'  => $order_id
            ];

            $response = $this->service->sendRequest($this->verifyPath, $data, $headers);
        
            $res = json_decode($response);
            
            if (isset($res)) {
                return $res;
            }
        }

        return false;        
    }
    /**
     * Redirect user to the received payment path.
     */
    public function gotoPayPath($link)
    {
        return redirect()->to($link);
    }

    /**
     * Check received data on payment callback.
     * 
     * @return boolean
     */
    public function receiveData($req)
    {
        if ($req->status && $req->id) {
            $this->status = $req->status;
            $this->track_id = $req->track_id;
            $this->id = $req->id;
            $this->card_no = $req->card_no;
            $this->hashed_card_no = $req->hashed_card_no;
            $this->date = $req->date;
            $this->order_id = $req->order_id;
            return $this;
        }

        return false;
    }

    /**
     * Get status code of payment.
     * 
     * @return number
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get track id of payment.
     * 
     * @return number
     */
    public function getTrackId()
    {
        return $this->track_id;
    }

    /**
     * Get track id of payment.
     * 
     * @return number
     */
    public function getOrderId()
    {
        return $this->order_id;
    }
    /**
     * Get id of payment.
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get customer cart number.
     * 
     * @return string
     */
    public function getCartNumber()
    {
        return $this->card_no;
    }

    /**
     * Get hashed cart number with SHA256 algorithm.
     * 
     * @return string
     */
    public function getHashedCartNumber()
    {
        return $this->hashed_card_no;
    }

    /**
     * Get hashed cart number with SHA256 algorithm.
     * 
     * @return timestamp
     */
    public function getDate()
    {
        return $this->date;
    }

}