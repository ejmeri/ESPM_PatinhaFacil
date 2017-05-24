<?php

namespace helper;


    Class Email
    {
        public function Send($to, $subject, $message)
        {
           
            $mail = new PHPMailer(); // instancia a classe PHPMailer

            $mail->IsSMTP();

            //configuração do gmail
            $mail->Port = '465'; //porta usada pelo gmail.
            $mail->Host = 'smtp.gmail.com'; 
            $mail->IsHTML(true); 
            $mail->Mailer = 'smtp'; 
            $mail->SMTPSecure = 'ssl';

            //configuração do usuário do gmail
            $mail->SMTPAuth = true; 
            $mail->Username = 'elmerisilva@gmail.com'; // usuario gmail.   
            $mail->Password = '**********'; // senha do email.

            $mail->SingleTo = true; 

            // configuração do email a ver enviado.
            $mail->From = "elmerisilva@gmail.com"; 
            $mail->FromName = "Elmeri Moreno."; 

            $mail->addAddress("leticiapasklan@gmail.com"); // email do destinatario.

            $mail->Subject = "Puta detected."; 
            $mail->Body = "Esse e-mail só chega para as putas.";

            if(!$mail->Send())
            echo "Erro ao enviar Email:" . $mail->ErrorInfo;
                }
            }
?>