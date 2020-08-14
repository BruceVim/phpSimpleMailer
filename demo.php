<?php
@header('Content-type: text/html;charset=UTF-8');
date_default_timezone_set('Asia/Shanghai');
ini_set('display_errors', 1);
require_once './phpSimpleMailerAutoload.php';


$savePath = '';
$webPath  = '';

$mailServer =

$mail = new PHPMailer;

//登录的账号 这里填入字符串格式的qq号即可
$mail->Username = 'qrfvim@qq.com';
// 这个就是之前得到的授权码，一共16位
$mail->Password = '***********';
//Imap服务
$mail->mailServer = 'imap.qq.com';
$mail->imapPort = '993';


$receiveObj = $mail->receiveConnect();
$unseen_mails = $mail->getUnseenMails(); //获取未读邮件数量

if(0 == count($unseen_mails)) { //如果信件数为0,显示信息
    $msg =  "No Unseen Message for ".$mail->Username;
    echo $msg;
    return array('msg'=>$msg);
}

foreach ($unseen_mails as $m_id) {
    $head=$mail->getHeaders($m_id);
    //处理邮件附件
    $files=$mail->GetAttach($m_id); // 获取邮件附件，返回的邮件附件信息数组

    $imageList=array();
    foreach($files as $k => $file)
    {
        //type=1为附件,0为邮件内容图片
        if($file['type'] == 0)
        {
            $imageList[$file['title']]=$file['pathname'];
        }
    }
    $body = $mail->getBody($m_id,$webPath,$imageList);

    $res['mail'][]=array(
        'head' => $head,
        'body' => $body,
        'attachList' => $files,
    );
    //操作逻辑
    $status = 0;

    if ($status == 0) {

        // qq 邮箱的 smtp服务器地址，这里当然也可以写其他的 smtp服务器地址
        $mail->Host = 'smtp.qq.com';
        $mail->setFrom('qrfvim@qq.com', 'send_user_name');
        //发送邮件
        $to = 'qiuruifeng@simuwang.com';
        $title = '会议主题';
        $content = '今天下午开会';
        $files_path = [];

        if (!empty($files)) {
            foreach ($files as $file) {
                $tmp = [];
                $tmp['path'] = dirname(__FILE__) . 'phpSimpleMailer/attachment/'.$file['pathname'];
                $tmp['title'] = $file['title'];
                $files_path[] = $tmp;
            }
        }
        $send_res = $mail->sendSMTPMail($to,$title,'今天下午开会', $files_path);
    }

    $mail->setFlagMails($m_id); //标志已读
}

$mail->closeReceiveMailbox();

