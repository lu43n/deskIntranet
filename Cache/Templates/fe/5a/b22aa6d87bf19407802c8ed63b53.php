<?php

/* employees/_helpBox.twig */
class __TwigTemplate_fe5ab22aa6d87bf19407802c8ed63b53 extends Twig_Template
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
        if (isset($context["request"])) { $_request_ = $context["request"]; } else { $_request_ = null; }
        if ((($this->getAttribute($_request_, "getActionName", array(), "method") == "view") || ($this->getAttribute($_request_, "getActionName", array(), "method") == "search"))) {
            // line 2
            echo "<div class=\"help_box_start\"></div>
<div class=\"help_box\">
    <h2 style=\"padding-top: 0;\">Akcje</h2>
    <ul>
        <li>
            <a href=\"";
            // line 7
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "employees", "action" => "index"), "admin"), "html", null, true);
            echo "\" style=\"font-weight:bold;\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/back_small.png\" alt=\"Powrót\" /> Powrót do listy</a>
        </li>
    </ul>
</div>
<div class=\"help_box_end\"></div> 
";
        }
        // line 13
        echo "
<div class=\"help_box_start\"></div>
<div class=\"help_box\">
    <h2 style=\"padding-top: 0;\">Szukaj</h2>
    <form action=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "employees", "action" => "search"), "admin", true), "html", null, true);
        echo "\" method=\"get\">
    <ul class=\"searchHelpBox\">
        <li>
            <input type=\"text\" name=\"keyword\" class=\"searchQuery\" />
        </li>
    </ul>
    </form>        
</div>
<div class=\"help_box_end\"></div>    ";
    }

    public function getTemplateName()
    {
        return "employees/_helpBox.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 17,  39 => 13,  27 => 7,  20 => 2,  17 => 1,  261 => 67,  253 => 64,  246 => 62,  238 => 61,  233 => 60,  228 => 57,  214 => 53,  203 => 52,  198 => 51,  195 => 50,  190 => 49,  173 => 46,  170 => 45,  165 => 44,  162 => 43,  155 => 42,  150 => 41,  138 => 40,  126 => 72,  121 => 70,  118 => 69,  114 => 38,  110 => 37,  100 => 34,  94 => 33,  89 => 32,  86 => 31,  83 => 30,  73 => 24,  68 => 21,  65 => 20,  52 => 10,  47 => 9,  44 => 8,  35 => 5,  32 => 4,  29 => 3,);
    }
}
