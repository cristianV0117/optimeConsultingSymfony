<?php
/**
 * @author Cristian Camilo Vasquez Osorio 17/12/20
 * Clase creada nativamente para validar el request del usuario
*/
namespace App\Core;

class Validator
{
    private $request;
    private $failed;

    public function validate($request)
    {
        $this->request = $request;
        return $this;
    }

    public function isNum()
    {
        if (!is_numeric($this->request)) {
            $this->failed = true;
        }

        return $this;
    }

    public function isPositive()
    {
        if ($this->request < 0) {
            $this->failed = true;
        }
        return $this;
    }

    public function specialCharacters()
    {
        if (!ctype_alpha($this->request)) {
            $this->failed = true;
        }
        
        return $this;
    }

    public function minMaxLength(int $min, int $max)
    {
        if (strlen($this->request) < $min || strlen($this->request) > $max) {
            $this->failed = true;
        }

        return $this;
    }

    

    public function failed()
    {
        return $this->failed;
    }

}