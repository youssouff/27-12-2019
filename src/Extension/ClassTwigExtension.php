<?php

namespace App\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;


class ClassTwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('instanceof', [$this, 'instanceOfTest']),
        ];
    }

    public function instanceOfTest($var, $instance)
    {
        return  $var instanceof $instance;
    }

}