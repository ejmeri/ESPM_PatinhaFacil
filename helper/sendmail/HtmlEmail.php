<?php

namespace helper\sendmail;

class HtmlEmail {
    public function GetHtmlAuth($nome, $hash)
    {
       return '<!DOCTYPE html>
<html>

<head>
    <title>E-mail</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="http://www.petfacil.net.br/">
    <link href="content/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="content/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="content/css/email/email.css?v=1.4" rel="stylesheet">

</head>

<body>
    <section class="section-email">
        <header class="header">
            <article class="text-center">
                <h1>PET.FÁCIL</h1>
                <img src="content/images/ejmeri-1.png" alt="logo">
                <hr class="small bg-color">                
            </article>
        </header>
        <section class="form-email">
            <header class="">
                <article class="clear">
                    <h4> Olá,'.$nome.' bem-vindo</h4>
                    <h4>A equipe PET.FÁCIL fica muito feliz por ter você como usuário. </h4>
                </article>

            </header>
            <br>
            <article class="text text-center">
                <h4>Para liberar o seu acesso, basta clicar no link abaixo:</h4>

                <a href="http://www.petfacil.net.br/login/autenticacao/'.$hash.'" class="text-white">
                    <div class="link">
                        Autenticação de acesso <i class="fa fa-paw"> </i> <i class="fa fa-unlock-alt"></i>
                    </div>
                </a>
            </article>

        </section>
        <footer id="contact">
            <div class="">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 text-center">
                        <h4 class="section-heading">Alguma dúvida ?</h4>
                        <hr class="primary">
                        <p>Entre em contato</p>
                    </div>
                    <div class="col-lg-4 col-lg-offset-2 text-center">
                        <i class="fa fa-phone fa-3x sr-contact"></i>
                        <p>(11) 91234-4321</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <i class="fa fa-envelope-o fa-3x sr-contact"></i>
                        <p><a href="mailto:feedback@petfacil.com.br">feedback@petfacil.com.br</a></p>
                    </div>
                </div>
            </div>

        </footer>
    </section>
</body>

</html>';
    }
}
?>