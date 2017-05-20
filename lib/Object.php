<?php

namespace lib;

 class Object {
    function __construct($method = null, $class = null) { // params-> Tipo de metodo do foem, e classe a ser gerado os objetos
        if($method == 'POST') {
            $tamanho = strlen($class); // pegar o tamanho da palavra para comparar com os indices do post
                foreach ($_POST as $ind => $val){
                
                $postclass = substr($ind,0, $tamanho); //get name class of post index

                if(strcasecmp($postclass, $class) == 0) { 
                    $ind = substr($ind, $tamanho);
                    $this->$ind = trim($val);
                }
                }
        }
    }
}
?>