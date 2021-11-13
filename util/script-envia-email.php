<?php
error_reporting(E_ERROR);
include_once '../util/PHPMailer/PHPMailer.php';

extract($_POST);
$error = array();

function isEmail($email) {
	return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email));
}

if($_POST) {
	if(empty($nome)){
		$error['nome'] =  "Por favor informe seu nome";
	}

	if(empty($email)){
		$error['email'] =  "Por favor informe seu email";
	}else if(!isEmail($email)){
		$error['email'] =  "Informe um email valido";
	}

	if(empty($telefone)){
		$error['telefone'] =  "Por favor informe seu telefone";
	}

	if(empty($message)){
		$error['message'] =  "Por favor digite a melhor data para a visita";
	}

	if(count($error)>0){
		 header("HTTP/1.0 406");
		 header('Content-type: application/json');
		 echo json_encode($error);
	}
	else {
		// Send email
		$body = wordwrap("<h2>Solicitação encaminhada por usuário através do site<br><small>www.sigsimple.com.br</small></h2>
						  <p><strong>Nome:</strong> $nome</p>
						  <p><strong>E-mail:</strong> $email</p>
						  <p><strong>Telefone:</strong> $telefone</p>
						  <p><strong>Mensagem:</strong> $message</p>"
						);

		// uncomment this to set the From and Reply-To emails, then pass the $headers variable to the "mail" function below
		$headers = "From: ".$email." <" . $email . ">" . "\r\n" . "Reply-To: " . $email;

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host     = "smtp.sigsimple.com.br";
		$mail->SMTPAuth = true;
		$mail->Username = 'comercial@sigsimple.com.br';
		$mail->Password = 'Senh@Comerci@l1';
		$mail->Port     = 587;

		$mail->From     = 'comercial@sigsimple.com.br';
		$mail->Sender   = "comercial@sigsimple.com.br";
		$mail->FromName = 'SIG Simple';

		$mail->AddAddress('comercial@sigsimple.com.br', '');

		$mail->IsHTML(true);

		$mail->Subject = utf8_decode("Solicitação de contato pelo site");
		$mail->Body = utf8_decode($body);

		$enviado = $mail->Send();

		$mail->ClearAllRecipients();
		$mail->ClearAttachments();

		if($enviado) {
			header("HTTP/1.0 202");
			echo 'Obrigado pelo seu contato! Em breve entraremos em contato.';
		}
		else {
			header("HTTP/1.0 505");
			echo "Desculpe, no momento o e-mail não pode ser enviado! Tente novamente mais tarde.";
			echo $mail->ErrorInfo;
		}
	}
}
else {
	header("HTTP/1.0 406");
	header('Content-type: application/json');
	echo "Nenhum campo foi preenchido!";
}

?>
