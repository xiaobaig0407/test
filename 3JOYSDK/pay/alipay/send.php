<?php
/* *
 * 功能：即时到账交易接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */

require_once("alipay.config.php");
require_once("lib/alipay_submit.class.php");
require_once dirname(__FILE__).'/'.'../../models/PayModel.php';


/**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/

class SendAlipay
{
    public function sendToAlipay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo)
    {
        //config
        $alipay_config = array();
        //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
        //合作身份者id，以2088开头的16位纯数字
        $alipay_config['partner']		= '2088611953545014';
        //nsky->2088011223173925
        //合作->2088511340144199

        //安全检验码，以数字和字母组成的32位字符
        //如果签名方式设置为“MD5”时，请设置该参数
        $alipay_config['key']			= 'ae8ozgqqhgwrv9810e8w5o9wplgaoavz';
        //nsky->fqgpmvwlhtl3x92pn8hr7chtcxnedm5n
        //合作->kvbcz5syk2l7pt5r6ddyeeb49799rfty

        //商户的私钥（后缀是.pen）文件相对路径
        //如果签名方式设置为“0001”时，请设置该参数
        $alipay_config['private_key_path']	= 'key/rsa_private_key.pem';
        //支付宝公钥（后缀是.pen）文件相对路径
        //如果签名方式设置为“0001”时，请设置该参数
        $alipay_config['ali_public_key_path']= 'key/alipay_public_key.pem';
        //签名方式 不需修改
        $alipay_config['sign_type']    = 'MD5';
        //字符编码格式 目前支持 gbk 或 utf-8
        $alipay_config['input_charset']= 'utf-8';
        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        $alipay_config['cacert']    = getcwd().'\\cacert.pem';
        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $alipay_config['transport']    = 'http';


        //返回格式
        $format = "xml";
        $v = "2.0";
        $req_id = date('Ymdhis');
        //服务器异步通知页面路径
        $notify_url = "http://sdk.3joy.cn/pay/alipay/notify_url.php";
        //页面跳转同步通知页面路径
        $call_back_url = "http://sdk.3joy.cn/pay/alipay/call_back_url.php";
        //操作中断返回地址
        $merchant_url = "http://sdk.3joy.cn/pay/alipay/call_back_url.php";
        //卖家支付宝帐户   公司-》16669667@qq.com，合作-》193977005@qq.com
        $seller_email = 'yangdongjie@9sky.me';
        //商户订单号
        $today = date("Ymd");
        $randNum = rand(1000, 9999);
        $out_trade_no = "$today"."$gameId"."$uid"."$randNum";//订单号
        //订单名称
        $subject = $itemName;
        //付款金额
        $total_fee = $orderMoney;
        //请求业务参数详细
        $req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';

        /************************************************************/

        //构造要请求的参数数组，无需改动
        $para_token = array(
            "service" => "alipay.wap.trade.create.direct",
            "partner" => trim($alipay_config['partner']),
            "sec_id" => trim($alipay_config['sign_type']),
            "format"	=> $format,
            "v"	=> $v,
            "req_id"	=> $req_id,
            "req_data"	=> $req_data,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestHttp($para_token);


        //URLDECODE返回的信息
        $html_text = urldecode($html_text);

        /*var_dump($html_text);

        die();*/

        //解析远程模拟提交后返回的信息
        $para_html_text = $alipaySubmit->parseResponse($html_text);

/*        para_html_text($para_html_text);

        die();*/
        //获取request_token
        $request_token = $para_html_text['request_token'];

        if($request_token)
        {
            $new = new PayModel();
            $insertPay = $new->insertAliPayOrder($partner,$gameId,$uid,$out_trade_no,$orderMoney,$itemName,$cpInfo,'0',time(),'0');
        }
        else
        {
            echo 'error';
        }


        /**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

        //业务详细
        $req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.wap.auth.authAndExecute",
            "partner" => trim($alipay_config['partner']),
            "sec_id" => trim($alipay_config['sign_type']),
            "format"	=> $format,
            "v"	=> $v,
            "req_id"	=> $req_id,
            "req_data"	=> $req_data,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, 'get');
        echo $html_text;
    }
}
