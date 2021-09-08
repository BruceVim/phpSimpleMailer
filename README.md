# phpSimpleMailer 
**PHPMailer改造版本** 
~~~~
SMTP发送邮件 （方法来自PHPMailer），
Imap接收邮件 原生PHP封装

ps : 云服务器 建议走 465端口 ssl协议发邮件

~~~~


使用方法
1.引入自动加载脚本

`
require_once './phpSimpleMailer/phpSimpleMailerAutoload.php';
`

2.配置邮箱账号、授权码信息、端口号等

~~~~
$mail = new phpSimpleMailer;
//登录的账号 这里填入字符串格式的qq号即可
$mail->Username = 'qrfvim@qq.com';
// 这个就是之前得到的授权码，一共16位
$mail->Password = '*****授权码********';
//Imap服务
$mail->mailServer = 'imap.qq.com';
$mail->imapPort = '993';
~~~~

3.邮件接收
~~~~
$receiveObj = $mail->receiveConnect();
$unseen_mails = $mail->getUnseenMails(); //获取未读邮件数量

foreach ($unseen_mails as $m_id) {
    $head=$mail->getHeaders($m_id);  //获取头部
    $files=$mail->GetAttach($m_id); // 获取邮件附件，返回的邮件附件信息数组
    $body = $mail->getBody($m_id,$webPath,$imageList); //获取邮件内容
}
~~~~

4.邮件发送 封装了PHPMailer的逻辑，直接调用即可，也可以看PHPMailer自行封装改造
~~~~
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
~~~~
