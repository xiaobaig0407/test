<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
"http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
<title>3卓网支付中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link href="./css/style.css" media="all" rel="stylesheet" type="text/css" />
<link href="css/css.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrap">
  <div class="span1_of_3">
    
    <!-- start span1_of_3 -->
    <div id="verticalTab" style="margin-top:2px;">
      <!-- start vertical Tabs-->
      <div class="resp-tabs-container" style="display:block;  width:100%;">
        <div class="new_posts" id="tab">
          <div class="vertical_post">
            <div class="content-box">
                 <p class="chongzhi-box"><img src="images/success.png"/><span class="chongzhi-box-text">恭喜您！成功充值<span style="color:#FF6600;"><?php echo $_GET['orderMoney']; ?></span>元</span></p>
                   <p  style="text-align:center; color:#009900;">订单号：<span> <?php echo $_GET['orderId']; ?> </span></p>
         <p style="text-align:center; font-size:14px; padding:10px;">请点击左上角按钮返回游戏!</p>           
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
  <div class="copy" style="font-size:14px;">
    <!-- start copy -->
    <p>充值服务商客服电话：<a href="tel:010-82615089"><span>010-82615089</span></a></p>
    <p> 北京闪卓互动科技有限公司 版权所有</p>
  </div>
</div>
</body>
</html>
