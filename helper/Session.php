<?php

namespace  helper;

Class Session
{
    public function __construct()
    {
    //    session_start();
       if(!(isset($_SESSION['PessoaId']))) 
       {
            header("Location: ". APP_ROOT. "/login");
       }
    }
}


?>
