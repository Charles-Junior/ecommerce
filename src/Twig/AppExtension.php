<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('tva', [$this, 'formatTva']),
            new TwigFilter('total', [$this, 'prixTotal']),
        ];
    }

    public function formatTva($val,$multiplicate)
    {
        $tva = $val * ($multiplicate / 100);
        $price = $val + $tva;

        return $price;
    }

    public function prixTotal($val,$multiplicate)
    {
        $prixTotal = $val * $multiplicate;

        return $prixTotal;
    }


}    