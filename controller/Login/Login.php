
<?php


date_default_timezone_set('America/Sao_Paulo');
// error_reporting(!E_NOTICE);

$dtInclusao = date('Y-m-d H:i:s');
$dtAtualizacao = date('Y-m-d H:i:s');

// Pessoa
$nome = $_POST['PessoaNome'];
$cpfcnpj = $_POST['PessoaCpfCnpj'];
// $cnpj = $_POST['Cnpj'];
$rg = $_POST['PessoaRg'];
$apelido = $_POST['PessoaApelido'];

// if(!empty($cpf)) {
//   $cpfcnpj = $cpf;
// }
// else {
//   $cpfcnpj = $cnpj;
// }

// E-mail
// $email = $_POST['EmailNome'];
// $tipoEmailId = $_POST['EmailTipoEmailId'];

// User
$login = $_POST['UsuarioLogin'];
$senha = $_POST['UsuarioSenha'];

// Telefone
// $numero = $_POST['Telefone'];
// $tipoTelefoneId = $_POST['tipoTelefone'];


// INICIO CLASSE PARA CRIACAO DOS OBJETOS COM SEUS VALORES
include('../../lib/Object.php');
// FIM CLASS CREATE OBJECT

// Model
include('../../lib/Model.php');
// Fim Model

// INICIO CLASSE BASE COM DATES E ID
include('../../object/EntityBase.php');
// FIM CLASSE BASE


// API'S
include('../../api/Pessoa/apiPessoa.php');
include('../../api/Email/apiEmail.php');
include('../../api/Usuario/apiUsuario.php');
// include('../../api/Telefone/apiTelefone.php');
// FIM API's

    class Login
    {
        public function savePessoa(Pessoa $pessoa){
          $api =  new apiPessoa();
          return $api->save($pessoa);
        }
        public function saveEmail(Email $email){
          $api =  new apiEmail();
          return $api->save($email);
        }
        public function saveUser(Usuario $usuario){
          $api =  new apiUsuario();
          return $api->save($usuario);
        }
        // public function saveTelefone(Telefone $telefone){
        //   $api =  new apiTelefone();
        //   return $api->save($telefone);
        // }
    }


// Instancia das Classes->Objects
$Login = new Login();
$Pessoa = new Pessoa();
$Email = new Email('POST', 'Email');
$Usuario = new Usuario();
// $Telefone = new Telefone();

// Objetos Pessoa
$Pessoa->Nome = $nome;
$Pessoa->Apelido = $apelido;
$Pessoa->Rg = $rg;
$Pessoa->CpfCnpj = $cpfcnpj;
$Pessoa->DtInclusao = $dtInclusao;
$Pessoa->DtAtualizacao = $dtAtualizacao;
$retorno = $Login->savePessoa($Pessoa);
$Pessoa->Id = $retorno['Identity']; // Retorno com Id inserido na tabela Pessoa, para Foreign Key nas tables abaixo


// Objetos Email
$Email->PessoaId = $Pessoa->Id;
$retornoEmail = $Login->saveEmail($Email);


// Objetos Usuário
$Usuario->Login = $login;
$Usuario->Senha = $senha;
$Usuario->PessoaId = $Pessoa->Id;
$Usuario->DtInclusao = $dtInclusao;
$Usuario->DtAtualizacao = $dtAtualizacao;
$retornoUser = $Login->saveUser($Usuario);

// Objetos Telefone
// $Telefone->Numero = $numero;
// $Telefone->TipoTelefoneId = $tipoTelefoneId;
// $Telefone->PessoaId = $Pessoa->Id;
// $Telefone->DtInclusao = $dtInclusao;
// $Telefone->DtAtualizacao = $dtAtualizacao;
// $retornoTelefone = $Login->saveTelefone($Telefone);


if($retorno['sucess'] && $retornoEmail['sucess'] && $retornoUser['sucess']) {
  echo '<h2>Parabéns '.$Pessoa->Nome.', você foi cadastrado em nosso sistema!</h2>
        <p>Faça seu login aqui <a href="login">Aqui</a>!</p>';
}
else {
   echo  $retorno['feedback']." <br> ".$retornoEmail['feedback']." <br>".$retornoUser['feedback'];
}

?>