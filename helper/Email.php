<?php

    namespace helper;

    Class Email
    {
        public function Send($to, $subject, $message)
        {
            $from = "elmerisilva@hotmail.com";

            $headers = 'From:'.$from;
            
            return mail($to, $subject, $message, $headers);
        }
    }
?>