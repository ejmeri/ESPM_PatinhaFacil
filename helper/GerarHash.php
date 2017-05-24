<?php
    namespace helper;

    Class GerarHash {
        public function Hash()
        {
            $rand = rand();
            $hash = hash('haval192,5',$rand);

            return $hash;
        }
    }
?>