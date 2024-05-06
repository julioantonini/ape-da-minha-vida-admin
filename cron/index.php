<?php
require_once "class/Conn.class.php";
require_once "class/Usuario.class.php";

include '../mautic/vendor/autoload.php'; 
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

$pdo = Conexao::getInstance();
$crud = Crud::getInstance($pdo, 'leads');
$id = $_POST['data'];
$sql = "SELECT * FROM leads WHERE sended = ?";
$sended = array(1);
$leads = $crud->getSQLGeneric($sql, $sended, TRUE);

foreach($leads as $lead){
    //
    // ApiAuth->newAuth() will accept an array of Auth settings
    $settings = array(
        'userName'   => 'admin',             // Create a new user       
        'password'   => 'Q8OYAi3O9bQhew48y'              // Make it a secure password
    );
    // Initiate the auth object specifying to use BasicAuth
    $initAuth = new ApiAuth();
    $auth = $initAuth->newAuth($settings, 'BasicAuth');
    //url
    $apiUrl = 'https://moraremfranca.com.br/marketing';
    $api = new MauticApi();

    $contactApi = $api->newApi('contacts', $auth, $apiUrl);
    $emailApi = $api->newApi('emails', $auth, $apiUrl);

    $dataContact = array(
        'firstname' => $lead->name,
        'email'     => $lead->email,
        'empreendimento' => $lead->empreendimento
    );

    //verifica etapa e faz o envio do email
    function sendMail($contact, $stap, $mail){
        switch ($stap) {
            case '1':
            $mail->sendToContact(2, $contact);
            break;
            case '2':
            $mail->sendToContact(3, $contact);
            break;
            case '3':
            $mail->sendToContact(4, $contact);
            break;
            case '4':
            $mail->sendToContact(5, $contact);
            break;
            case '5':
            $mail->sendToContact(6, $contact);
            break;
            case '6':
            $mail->sendToContact(7, $contact);
            break;
            case '7':
            $mail->sendToContact(8, $contact);
            break;
        }
    }

    function sendSMS($stap, $phone, $name){
        //api sms
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api-rest.zenvia.com/services/send-sms");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        //
        $username = 'lividigital.web';
        $password = 'uvDS6amWWC';

        $uid = uniqid();

        $date = date("Y-m-d").'T'.date("H:i:s");

        $phone = '55'.$phone;

        switch ($stap) {
            case '1':
            $mensagem = 'VITTA: '.$name.', recebemos seu pedido de simulado. Em poucos instantes, nossos especialistas entrarão em contato com você =)';
            break;
            case '2':
            $mensagem = 'VITTA: '.$name.', você que está interessado no apartamento do Collis Residence. Qual horário podemos conversar?';
            break;
            case '3':
            $mensagem = 'VITTA: '.$name.', será um prazer receber você! Esperamos você em nossa visita. Veja seu e-mail, lá tem recomendações importantes. Até breve!';
            break;
            case '4':
            $mensagem = 'VITTA: '.$name.', não vamos desistimos de realizar seu sonho. Em breve venho com novidades. Qualquer dúvida estaremos no whatsApp.';
            break;
            case '5':
            $mensagem = 'VITTA: '.$name.', não vamos desistimos de realizar seu sonho. Em breve venho com novidades. Qualquer dúvida estaremos no whatsApp.';
            break;
            case '6':
            $mensagem = 'VITTA: '.$name.', não vamos desistimos de realizar seu sonho. Em breve venho com novidades. Qualquer dúvida estaremos no whatsApp.';
            break;
            case '7':
            $mensagem = 'VITTA: '.$name.', Muito obrigado! Estamos felizes pela sua conquista do seu apartamento Vitta.  Conte Conosco!';
            break;
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
            \"sendSmsRequest\": {
            \"from\": \" \",
            \"to\": \"$phone\",
            \"schedule\": \"$date\",
            \"msg\": \"$mensagem\",
            \"callbackOption\": \"NONE\",
            \"id\": \"$uid\",
            \"flashSms\": false
            }
        }");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode($username . ':' . $password),
            "Accept: application/json"
        ));

        $response = curl_exec($ch);
        curl_close($ch);
    }

    //verifica se lead já existe na base mautic
    $where = 'email:'.$lead->email;
    $contacts = $contactApi->getList($where);
    if($contacts['total']==0){
        $contact = $contactApi->create($dataContact);
        sendMail($contact['contact']['id'], 1, $emailApi);
    }else{
        $idContact = array_slice($contacts['contacts'], 0);
        sendMail($idContact[0]['fields']['all']['id'], 1, $emailApi);
    }
    //envio sms
    $phone = $lead->phone;
    $name = $lead->name;
    sendSMS(1, $phone, $name);

    //
    $arrayLead = array('sended' => 0);
    $arrayCond = array('id=' => $lead->id);
    $crud->update($arrayLead, $arrayCond);
}