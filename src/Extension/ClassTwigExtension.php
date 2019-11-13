<?php

namespace App\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;


class ClassTwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('class', [$this, 'getClassName']),
        ];
    }

    public function getClassName($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }

}