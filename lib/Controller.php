<?php


namespace lib;

class Controller extends System {

    public $autor = "Elmeri Moreno Da Silva - ESPM";
    public $dados;
    public $layout = '_layout';
    private $path;
    private $pathRender;
    //View web
    protected $title = null;
    protected $description = null;
    protected $keywords = 'Adotar, animação de estimação, doação, gatos, animais abandonados, animais, abandonados';
    protected $image;
    protected $captionController;
    protected $captionAction;
    protected $captionParams;

    public function __construct(){
        parent::__construct();
    }

    private function setPath($render){
        if (is_array($render)){
            foreach ($render as $li) {
                $path = 'view/' . $this->getArea() . '/'. $this->getController() . '/' . $li . '.phtml';
                $this->fileExists($path);
                $this->path[] = $path;
            }
        } else {
            $this->pathRender = is_null($render)? $this->getAction() : $render;
            $this->path = 'view/' . $this->getArea() . '/' . $this->getController() . '/' . $this->pathRender . '.phtml';
            $this->fileExists($this->path);
        }
    }

    private function fileExists($file){
        if (!file_exists($file)){
            die('Não foi localizado o arquivo ' . $file);
        }
    }

    public function View($render = null){
        $this->title = is_null($this->title) ? 'Pet Fácil' : $this->title;
        $this->description = is_null($this->description) ? 'Minha descrição' : $this->description;
        $this->keywords = is_null($this->keywords) ? 'Minha palavra chave' : $this->keywords;

        $this->setPath($render);

        if (is_null($this->layout)){
            $this->Render();
        } else {
            $this->layout = "content/{$this->getArea()}/shared/{$this->layout}.phtml";
            if (file_exists($this->layout)){
                $this->Render($this->layout);
            } else {
                die("Não foi possivel localizar o layout");
            }
        }
    }
    public function PartialView($render = null) {  
            
            $this->setPath($render);

            if (!is_null($this->layout)){
                $this->render();
        } 
    }
    public function PartialResultView($value='')
    {
        echo $value;
    }
    public function Render($file = null){
        if (is_array($this->dados) && count($this->dados) > 0){
            extract($this->dados, EXTR_PREFIX_ALL, 'view');
            extract(array(
                'controller'=>(is_null($this->captionController) ? '' : $this->captionController),
                'action'=>(is_null($this->captionAction) ? '' : $this->captionAction),
                'params'=>(is_null($this->captionParams) ? '' : $this->captionParams)
            ), EXTR_PREFIX_ALL, 'caption');
        }

        if (!is_null($file) && is_array($file)){
            foreach ($file as $li){
                include($li);
            }
        } elseif (is_null($file) && is_array($this->path)){
            foreach ($this->path as $l) {
                include($l);
            }
        } else {
            $file = is_null($file) ? $this->path : $file;
            file_exists($file) ? include ($file) : die($file);
        }
    }
}