<?php

/* error/error.twig */
class __TwigTemplate_48ea930eb7d447f45afa166780a2de42 extends Twig_Template
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

    <title>Błąd 404 - deskCMS</title>

</head>

<body>
    <h1>Wystąpił nieoczekiwany błąd</h1>
    <h2>";
        // line 13
        if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
        echo twig_escape_filter($this->env, $_message_, "html", null, true);
        echo "</h2>

    ";
        // line 15
        if (array_key_exists("exception", $context)) {
            // line 16
            echo "
    <h3>Informacje o wyjątku:</h3>
    <p>
    <b>Wiadomość:</b> ";
            // line 19
            if (isset($context["exception"])) { $_exception_ = $context["exception"]; } else { $_exception_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_exception_, "getMessage", array(), "method"), "html", null, true);
            echo "
    </p>

    <h3>Śledzenie wyjątku:</h3>
    <pre>";
            // line 23
            if (isset($context["exception"])) { $_exception_ = $context["exception"]; } else { $_exception_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_exception_, "getTraceAsString", array(), "method"), "html", null, true);
            echo "</pre>

    <h3>Parametry żądania:</h3>
    <pre>";
            // line 26
            if (isset($context["request"])) { $_request_ = $context["request"]; } else { $_request_ = null; }
            echo twig_var_dump($this->env, $context, $this->getAttribute($_request_, "getParams", array(), "method"));
            echo "
    </pre>

    ";
        }
        // line 30
        echo "</body>
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
        return array (  67 => 30,  59 => 26,  52 => 23,  44 => 19,  39 => 16,  37 => 15,  31 => 13,  17 => 1,);
    }
}
