<?php

/* pm/_helpBox.twig */
class __TwigTemplate_ae7b74aa0dec02b68c7b3916eb60fa79 extends Twig_Template
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
        echo "<div class=\"help_box_start\"></div>
<div class=\"help_box\">
    <h2 style=\"padding-top: 0;\">Akcje</h2>
    <ul>
        <li>
            <a href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "create"), "admin"), "html", null, true);
        echo "\" style=\"font-weight:bold;\"><img src=\"";
        if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
        echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
        echo "/images/admin/send_small.png\" alt=\"Stwórz nową\" /> Stwórz nową</a>
        </li>

        ";
        // line 9
        if (isset($context["request"])) { $_request_ = $context["request"]; } else { $_request_ = null; }
        if (($this->getAttribute($_request_, "getActionName", array(), "method") == "view")) {
            // line 10
            echo "        <li>
            <a href=\"";
            // line 11
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "index"), "admin", true), "html", null, true);
            echo "\" style=\"font-weight:bold;\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/back_small.png\" alt=\"Powrót\" /> Powrót do listy</a>
        </li>
        ";
        }
        // line 14
        echo "        <li id=\"delete-messages\" style=\"display: none;\">
            <a href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "delete-messages"), "admin"), "html", null, true);
        echo "\" style=\"font-weight:bold;\"><img src=\"";
        if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
        echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
        echo "/images/admin/delete_small.png\" alt=\"Usuń zaznaczone\" /> Usuń zaznaczone</a>
        </li>
    </ul>
</div>
<div class=\"help_box_end\"></div> ";
    }

    public function getTemplateName()
    {
        return "pm/_helpBox.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 14,  39 => 11,  36 => 10,  33 => 9,  24 => 6,  17 => 1,  333 => 148,  277 => 95,  249 => 70,  239 => 62,  217 => 58,  209 => 57,  200 => 55,  176 => 54,  168 => 53,  160 => 52,  155 => 51,  141 => 50,  123 => 49,  107 => 35,  103 => 34,  93 => 31,  87 => 30,  82 => 29,  79 => 28,  76 => 27,  61 => 15,  57 => 14,  51 => 15,  46 => 9,  43 => 8,  34 => 5,  31 => 4,  28 => 3,);
    }
}
