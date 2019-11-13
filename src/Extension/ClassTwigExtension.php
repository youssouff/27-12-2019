<?php

namespace App\Extension;

use Twig\TwigTest;
use Twig\Extension\AbstractExtension;


class ClassTwigExtension extends AbstractExtension
{
    public function getTests()
    {
        return [
            new TwigTest('instanceof', 'isInstanceOf'),
        ];
    }

    /**
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceOf($var, $instance) {
        return  $var instanceof $instance;
    }

}