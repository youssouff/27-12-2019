<?php

namespace App\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ClassTwigExtension extends AbstractExtension
{
    public function getTests()
    {
        return [
            'instanceof' =>  new \Twig_Function_Method($this, 'isInstanceof')
        ];
    }

    /**
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceof($var, $instance) {
        return  $var instanceof $instance;
    }
}