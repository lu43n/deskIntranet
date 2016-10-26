<?php

/* error/error.twig */
class __TwigTemplate_34178115019d3ee6a937078894945f4a extends Twig_Template
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
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pl\" lang=\"pl\">
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <meta http-equiv=\"Content-Language\" content=\"pl\" />

    <title>Błąd - Panel Administracyjny - deskCMS</title>

    <link rel=\"stylesheet\" href=\"";
        // line 9
        if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
        echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
        echo "/css/admin/style.css\" type=\"text/css\" />

</head>

<body>

    <div id=\"wrap\">
        <div id=\"top\">
                <a href=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "index", "action" => "index"), "admin", true), "html", null, true);
        echo "\" class=\"logo\"><img src=\"";
        if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
        echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
        echo "/images/admin/panel_logo.png\" alt=\"deskCMS\" /></a>
        </div>

        <div id=\"main\">
            <div class=\"full-side\">
                <div class=\"content\">
                    <h1>Wystąpił nieoczekiwany błąd</h1>
                    <h2>";
        // line 24
        if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
        echo twig_escape_filter($this->env, $_message_, "html", null, true);
        echo "</h2>

                    ";
        // line 26
        if (array_key_exists("exception", $context)) {
            // line 27
            echo "
                    <h3>Informacje o wyjątku:</h3>
                    <p>
                    <b>Wiadomość:</b> ";
            // line 30
            if (isset($context["exception"])) { $_exception_ = $context["exception"]; } else { $_exception_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_exception_, "getMessage", array(), "method"), "html", null, true);
            echo "
                    </p>

                    <h3>Śledzenie wyjątku:</h3>
                    <pre>";
            // line 34
            if (isset($context["exception"])) { $_exception_ = $context["exception"]; } else { $_exception_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_exception_, "getTraceAsString", array(), "method"), "html", null, true);
            echo "</pre>

                    <h3>Parametry żądania:</h3>
                    <pre>";
            // line 37
            if (isset($context["request"])) { $_request_ = $context["request"]; } else { $_request_ = null; }
            echo twig_var_dump($this->env, $context, $this->getAttribute($_request_, "getParams", array(), "method"));
            echo "
                    </pre>
                    ";
        } else {
            // line 40
            echo "                    <p>Powiadom administratora o tym fakcie pisząc na adres <a href=\"";
            if (isset($context["developer"])) { $_developer_ = $context["developer"]; } else { $_developer_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_developer_, "email"), "html", null, true);
            echo "\">";
            if (isset($context["developer"])) { $_developer_ = $context["developer"]; } else { $_developer_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_developer_, "email"), "html", null, true);
            echo "</a></p>
                    ";
        }
        // line 42
        echo "                </div>           
            </div>
        </div>

    </div>

</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "error/error.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 42,  87 => 40,  80 => 37,  73 => 34,  65 => 30,  60 => 27,  58 => 26,  52 => 24,  39 => 17,  27 => 9,  17 => 1,);
    }
}
