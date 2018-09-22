<?php

namespace helper\sendmail;


    Class sendEmail
    {
        public function Send($to, $subject, $message, $tipoemail = 'no-reply', $replyto = '', $namereplyto = '')
        {
            $mail = new PhpMailer(); // instancia a classe PHPMailer

            $mail->IsSMTP();

            //configuração do gmail
            $mail->Port = '465'; //porta usada pelo gmail.
            $mail->Host = 'br938.hostgator.com.br'; 
            $mail->IsHTML(true); 
            $mail->Mailer = 'smtp'; 
            $mail->SMTPSecure = 'ssl';
            $mail->CharSet="UTF-8";

            //configuração do usuário do gmail
            $mail->SMTPAuth = true; 
            $mail->Username = $tipoemail.'@patinhafacil.com'; // usuario gmail.
            $mail->Password = 'p3t.f4c1l'; // senha do email. 
            $mail->SingleTo = true; 

            // configuração do email a ver enviado.
            $mail->From = $tipoemail.'@patinhafacil.com'; 
            $mail->FromName = "Equipe Patinha Fácil - <$mail->From>"; 

            if($replyto != '' || isset($replyto))
                $mail->AddReplyTo($replyto, $namereplyto);
            $mail->addAddress($to); // email do destinatario.

            $mail->Subject = $subject; 
            $mail->Body = $message;

            if(!$mail->Send()) return "Erro ao enviar Email:" . $mail->ErrorInfo;
            else return "ok";
        }
    }
?>