<?php

namespace api;

use helper\sendmail\sendEmail;
use helper\sendmail\HtmlEmail;


    class apiAjuda
    {
        public function EmailParaQuemDoar($Email, $Nome, $AnimalId)
        {
            $SendEmail = new sendEmail();
            $message = file_get_contents('content/site/shared/emails/header-email.html');
            $message .= file_get_contents('content/site/shared/emails/_paraquemdoar.html');
            $message .= file_get_contents('content/site/shared/emails/footer-email.html');

            $replacements = array(
                '({pessoa})' => $Nome,
                '({petid})' => $AnimalId . hash('md5', $AnimalId)
            );

            $message = preg_replace( array_keys( $replacements ), array_values( $replacements ), $message );
            
            $retorno = $SendEmail->Send($Email, 'Um pet que talvez lhe agrade', $message, 'contato');
            
            return $retorno;
        }
    }

?>
