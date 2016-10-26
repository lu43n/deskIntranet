<?php

/* _forms\buttons.twig */
class __TwigTemplate_366769ec8fec9cc6a34d7fd28b6cc44d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"buttons\">
    ";
        // line 2
        if (isset($context["elements"])) { $_elements_ = $context["elements"]; } else { $_elements_ = null; }
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($_elements_);
        foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
            // line 3
            echo "        ";
            if (isset($context["element"])) { $_element_ = $context["element"]; } else { $_element_ = null; }
            echo $this->getAttribute($_element_, "render", array(), "method");
            echo "
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 5
        echo "</div>";
    }

    public function getTemplateName()
    {
        return "_forms\\buttons.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  25 => 3,  40 => 6,  34 => 4,  20 => 2,  91 => 8,  88 => 7,  74 => 6,  38 => 4,  32 => 2,  21 => 1,  17 => 1,  430 => 144,  425 => 139,  420 => 115,  415 => 54,  410 => 16,  397 => 145,  395 => 144,  392 => 143,  387 => 140,  385 => 139,  371 => 131,  366 => 128,  352 => 124,  347 => 123,  341 => 122,  336 => 121,  329 => 116,  327 => 115,  313 => 109,  294 => 108,  287 => 103,  284 => 102,  278 => 98,  269 => 95,  259 => 91,  252 => 90,  245 => 89,  238 => 88,  231 => 87,  225 => 85,  214 => 80,  208 => 78,  197 => 73,  190 => 72,  183 => 71,  177 => 69,  168 => 66,  165 => 65,  162 => 64,  155 => 63,  145 => 55,  142 => 54,  136 => 51,  128 => 46,  124 => 45,  120 => 44,  116 => 43,  112 => 42,  108 => 41,  96 => 32,  89 => 27,  86 => 26,  81 => 24,  77 => 23,  73 => 22,  69 => 21,  61 => 19,  57 => 5,  54 => 17,  52 => 16,  47 => 14,  43 => 13,  39 => 12,  35 => 5,  24 => 2,  22 => 1,  65 => 20,  56 => 22,  50 => 19,  30 => 4,  27 => 3,);
    }
}
