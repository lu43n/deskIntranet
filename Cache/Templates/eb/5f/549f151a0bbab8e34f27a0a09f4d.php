<?php

/* employees/view.twig */
class __TwigTemplate_eb5f549f151a0bbab8e34f27a0a09f4d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout.twig");

        $this->blocks = array(
            'breadcrumbs' => array($this, 'block_breadcrumbs'),
            'javascripts' => array($this, 'block_javascripts'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'helpbox' => array($this, 'block_helpbox'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_breadcrumbs($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $context["breadcrumbsList"] = array(0 => array("url" => $this->env->view->getHelper("url")->url(array("controller" => "employees"), "admin", true), "label" => "Struktura pracowników"));
        // line 5
        echo "    ";
        if (isset($context["cms"])) { $_cms_ = $context["cms"]; } else { $_cms_ = null; }
        if (isset($context["breadcrumbsList"])) { $_breadcrumbsList_ = $context["breadcrumbsList"]; } else { $_breadcrumbsList_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_cms_, "breadcrumbs", array(0 => $_breadcrumbsList_), "method"), "html", null, true);
        echo "
";
    }

    // line 8
    public function block_javascripts($context, array $blocks = array())
    {
        // line 9
        echo "    <script type=\"text/javascript\">
    \$(document).ready(function(){   
        
        \$('#userData a[href=\"#openChat\"]').click(function () {
            chat.openChat(\$(this).attr('rel'));
            return false;
        });
     
    });
    </script>
";
    }

    // line 21
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 22
        echo "    <style type=\"text/css\">
            #userData {
                margin: 10px 0 0 0;
            }
            #userData li {
                list-style: none;
                font-size: 14px;
                margin: 10px 0;
            }
    </style>
";
    }

    // line 34
    public function block_helpbox($context, array $blocks = array())
    {
        // line 35
        echo "    ";
        $this->env->loadTemplate("employees/_helpBox.twig")->display($context);
    }

    // line 38
    public function block_content($context, array $blocks = array())
    {
        echo "                   

<div class=\"right-side\">
    <div class=\"content\">
        <h1>Profil ";
        // line 42
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_user_, "firstname"), "html", null, true);
        echo " ";
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_user_, "lastname"), "html", null, true);
        echo " (";
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_user_, "username"), "html", null, true);
        echo "):</h1>
        <div style=\"clear: both; overflow: hidden;\">
            <img src=\"";
        // line 44
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        if ($this->getAttribute($_user_, "photo")) {
            if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($_user_, "photo"), 0), "url"), "html", null, true);
        } else {
            echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
            echo "/images/admin/no-avatar.png";
        }
        echo "\" alt=\"Zdjęcie profilowe\" style=\"float: left; width: 460px; margin: 0 15px 15px 0;\" />
            <ul id=\"userData\">
                <li>Imię i nazwisko:<br/> <strong>";
        // line 46
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_user_, "firstname"), "html", null, true);
        echo " ";
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_user_, "lastname"), "html", null, true);
        echo "</strong></li>
                <li>Nazwa użytkownika:<br/> <strong>";
        // line 47
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_user_, "username"), "html", null, true);
        echo "</strong></li>
                ";
        // line 48
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        if ($this->getAttribute($_user_, "mobile")) {
            // line 49
            echo "                <li>Numer telefonu:<br/> <strong>";
            if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_user_, "mobile"), "html", null, true);
            echo "</strong></li>
                ";
        }
        // line 51
        echo "                <li>&nbsp;</li>
                <li>Ostatnie logowanie:<br/> <strong>";
        // line 52
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_user_, "last_login"), "html", null, true);
        echo "</strong></li>
                <li>Data rejestracji:<br/> <strong>";
        // line 53
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_user_, "created_at"), "html", null, true);
        echo "</strong></li>
                <li>&nbsp;</li>
                ";
        // line 55
        if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
        if (isset($context["identity"])) { $_identity_ = $context["identity"]; } else { $_identity_ = null; }
        if (($this->getAttribute($_user_, "uid") != $this->getAttribute($_identity_, "uid"))) {
            // line 56
            echo "                <li><strong><a href=\"";
            if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "create", "recipient" => $this->getAttribute($_user_, "uid")), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
            echo "/images/admin/send_small.png\" alt=\"Wyślij wiadomość prywatną\" class=\"icon\" /> Wyślij wiadomość prywatną</a></strong></li>
                <li><strong><a href=\"#openChat\" rel=\"";
            // line 57
            if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_user_, "uid"), "html", null, true);
            echo "\"><img src=\"";
            echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
            echo "/images/admin/all_chats.png\" alt=\"Rozpocznij czat\" class=\"icon\" /> Rozpocznij czat</a></strong></li>
                ";
        }
        // line 59
        echo "            </ul>
        </div>
    </div>
</div>

";
    }

    public function getTemplateName()
    {
        return "employees/view.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  176 => 59,  168 => 57,  160 => 56,  156 => 55,  150 => 53,  145 => 52,  142 => 51,  135 => 49,  132 => 48,  127 => 47,  119 => 46,  107 => 44,  95 => 42,  87 => 38,  82 => 35,  79 => 34,  65 => 22,  62 => 21,  48 => 9,  45 => 8,  36 => 5,  33 => 4,  30 => 3,);
    }
}
