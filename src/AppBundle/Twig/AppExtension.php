<?php
namespace AppBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

class AppExtension extends \Twig_Extension
{
    protected $request;

    public function setRequest(RequestStack $request_stack)
    {
        $this->request = $request_stack->getCurrentRequest();
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        //Depende del idioma seleccionado se usará una moneda
        if ($this->request->getLocale() == "es" || $this->request->getLocale() == "fr") {
            $price = $price . " €";
        } else {
            $price = ($price * 0.89) . " £";    
        }

        return $price;
    }

    public function getName()
    {
        return 'app_extension';
    }
}