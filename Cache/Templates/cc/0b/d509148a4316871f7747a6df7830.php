<?php

/* login/index.twig */
class __TwigTemplate_cc0bd509148a4316871f7747a6df7830 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout.twig");

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
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
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 4
        echo "    <link rel=\"stylesheet\" href=\"";
        if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
        echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
        echo "/css/admin/login.css\" type=\"text/css\" />
    
    <style type=\"text/css\">
        #main .form fieldset .field label {
            float:none;
            text-align: left;
        }
                
        #main .form fieldset .field input, #main .content .form fieldset .field textarea {
            width: 97%;
            max-width: none;
        }
        
    </style>
    
    ";
        // line 19
        $this->displayParentBlock("stylesheets", $context, $blocks);
        echo "
";
    }

    // line 22
    public function block_content($context, array $blocks = array())
    {
        echo "                   

<div class=\"right-side\">
    <div class=\"content\">
        <h1>Logowanie</h1>
        ";
        // line 27
        if (isset($context["loginForm"])) { $_loginForm_ = $context["loginForm"]; } else { $_loginForm_ = null; }
        echo $_loginForm_;
        echo "
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "login/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  65 => 27,  56 => 22,  50 => 19,  30 => 4,  27 => 3,);
    }
}
