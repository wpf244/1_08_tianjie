<?php

namespace app\index\controller;



use think\Controller;

use think\Loader;


Loader::import('threepay.notify',EXTEND_PATH);
class Pays extends Controller

{

    public function notifyurl()

    {

        $key="8cd297f1308c21f209179ef6d539d14991f34809";
        
        $merchant_id        = trim(input('merchant_id'));

        $status        = trim(input('status'));

        $order_no         = trim(input('order_no'));
        
        $total_fee         = trim(input('total_fee'));
        
        $out_trade_no      = trim(input('out_trade_no'));
        
        $pay_type         = trim(input('pay_type'));
        
        $sign              = trim(input('sign'));

       
        
        $sing = "merchant_id=$merchant_id&status=$status&order_no=$order_no&out_trade_no=$out_trade_no&total_fee=$total_fee&pay_type=$pay_type&$key";

        $signs=md5($sing);
        
        if($sign == $signs){
            if($status == 1){
            
            
            
                $re=db("recharge")->where("orderid= '$out_trade_no' ")->find();
            
                if($re['status'] == 0){
            
                   $res=db("recharge")->where("orderid= '$out_trade_no' ")->setField("status",1);
                     
                    $uid=$re['uid'];
            
                    db("user")->where("uid=$uid")->setInc("money",$total_fee);
                    $user = db("user")->where("uid", $uid)->find();
                    if($user['status'] == 0){
                        db("user")->where("uid", $uid)->setField("status",1);
                    }
            
               }
            
            }
        }

        

        

      

    }

    public function index()

    {

    //    $this->redirect("User/index");

        header('Content-Type:textml;charset=GB2312');

        $orderid        = trim(input('orderid'));

        $opstate        = trim(input('opstate'));

        $ovalue         = trim(input('ovalue'));

        $sign           = trim(input('sign'));

        



        if($opstate == 0){

          

            $signkey= 'b5c6efa9854545b98fec30c9d315d3e5';

            //$signkey = $channel_model->where($channel_where)->getField('signkey');



            $sign_text  = "orderid=$orderid&opstate=$opstate&ovalue=$ovalue".$signkey;

            $sign_md5 = md5($sign_text);

            if($sign_md5 == $sign){

                $re=db("recharge")->where("orderid= '$orderid' ")->find();

                if($re['status'] == 0){

                    $res=db("recharge")->where("orderid= '$orderid' ")->setField("status",1);

                }

                $uid=$re['uid'];

                db("user")->where("uid=$uid")->setInc("money",$ovalue);
                $user = db("user")->where("uid", $uid)->find();
                if($user['status'] == 0){
                    db("user")->where("uid", $uid)->setField("status",1);
                }

                $this->redirect("User/index");

               

            }

        }

        $this->redirect("User/index");

    }

}