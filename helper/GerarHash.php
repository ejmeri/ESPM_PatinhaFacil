<?php
    namespace helper;

    Class GerarHash {
        public function HashAuth()
        {
            $rand = rand();
            $hash = hash('haval192,5',$rand);

            return $hash;
        }
        public function Hash($valor,$hashtype = '')
        {
            if($hashtype == '')
            {
                $hashtype = 'camellia-256-ecb';
            }

            return openssl_encrypt($valor, $hashtype,'[gOFdl+(BF[(');
        }
        public function Unhash($valor, $hashtype = '')
        {
            if($hashtype == '')
            {
                $hashtype = 'camellia-256-ecb';
            }
            
            return openssl_decrypt($valor, $hashtype,'[gOFdl+(BF[(');
        }
    }
?>