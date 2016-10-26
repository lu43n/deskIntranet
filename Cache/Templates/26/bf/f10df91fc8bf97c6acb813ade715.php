<?php

/* employees/index.twig */
class __TwigTemplate_26bff10df91fc8bf97c6acb813ade715 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout.twig");

        $this->blocks = array(
            'breadcrumbs' => array($this, 'block_breadcrumbs'),
            'javascripts' => array($this, 'block_javascripts'),
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
        echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/css/admin/jquery.tree.css\" />
    <script type=\"text/javascript\" src=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/jquery.tree.js\"></script>
    <script type=\"text/javascript\">
    \$(document).ready(function(){    
     
     \$('#tree').treeview();
     
    });
    </script>
";
    }

    // line 20
    public function block_helpbox($context, array $blocks = array())
    {
        // line 21
        echo "    ";
        $this->env->loadTemplate("employees/_helpBox.twig")->display($context);
    }

    // line 24
    public function block_content($context, array $blocks = array())
    {
        echo "                   

<div class=\"right-side\">
    <div class=\"content\">
        <h1>Struktura pracowników:</h1>
        
        ";
        // line 30
        if (isset($context["flashMessages"])) { $_flashMessages_ = $context["flashMessages"]; } else { $_flashMessages_ = null; }
        if ($_flashMessages_) {
            // line 31
            echo "        <div class=\"messages\">
            ";
            // line 32
            if (isset($context["flashMessages"])) { $_flashMessages_ = $context["flashMessages"]; } else { $_flashMessages_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_flashMessages_);
            foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                // line 33
                echo "                <div class=\"message ";
                if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_message_, "type"), "html", null, true);
                echo "\">
                    ";
                // line 34
                if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_message_, "message"), "html", null, true);
                echo "
                </div> 
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 37
            echo "        </div>
        ";
        }
        // line 38
        echo "           

";
        // line 69
        echo "
<h3 style=\"margin: 0 0 3px 3px;\"><img src=\"";
        // line 70
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/images/admin/company_small.png\" alt=\"Firma\" class=\"icon\" /> Firma</h3>
<ul id=\"tree\" style=\"margin-left: 3px;\">
";
        // line 72
        if (isset($context["employees"])) { $_employees_ = $context["employees"]; } else { $_employees_ = null; }
        echo $this->getAttribute($this, "tree", array(0 => 0, 1 => $_employees_), "method");
        echo "  
</ul>
        
    </div>
</div>

";
    }

    // line 40
    public function gettree($gid = null, $employees = null)
    {
        $context = $this->env->mergeGlobals(array(
            "gid" => $gid,
            "employees" => $employees,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 41
            echo "    ";
            if (isset($context["employees"])) { $_employees_ = $context["employees"]; } else { $_employees_ = null; }
            if (isset($context["gid"])) { $_gid_ = $context["gid"]; } else { $_gid_ = null; }
            if ($this->getAttribute($_employees_, $_gid_, array(), "array")) {
                // line 42
                echo "        ";
                if (isset($context["employees"])) { $_employees_ = $context["employees"]; } else { $_employees_ = null; }
                if (isset($context["gid"])) { $_gid_ = $context["gid"]; } else { $_gid_ = null; }
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($_employees_, $_gid_, array(), "array"));
                foreach ($context['_seq'] as $context["_key"] => $context["employee"]) {
                    // line 43
                    echo "        <li>
            <span>";
                    // line 44
                    if (isset($context["employee"])) { $_employee_ = $context["employee"]; } else { $_employee_ = null; }
                    echo twig_escape_filter($this->env, $this->getAttribute($_employee_, "title"), "html", null, true);
                    echo "</span>
            ";
                    // line 45
                    if (isset($context["employee"])) { $_employee_ = $context["employee"]; } else { $_employee_ = null; }
                    if ($this->getAttribute($_employee_, "users")) {
                        // line 46
                        echo "            <ul style=\"margin: 0; padding-bottom: 10px; ";
                        if (isset($context["employees"])) { $_employees_ = $context["employees"]; } else { $_employees_ = null; }
                        if (isset($context["employee"])) { $_employee_ = $context["employee"]; } else { $_employee_ = null; }
                        if ($this->getAttribute($_employees_, $this->getAttribute($_employee_, "gid"), array(), "array")) {
                            echo "background: url(";
                            echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
                            echo "/images/admin/treeview-default-line.gif) 0 0 no-repeat;";
                        } else {
                            echo "background: url(";
                            echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
                            echo "/images/admin/treeview-default-line.gif) 0 0 no-repeat; background-position: 0 -1766px;";
                        }
                        echo "\">
                    <li style=\"margin: 5px 0 0 17px; padding: 10px; border: 1px dotted #000; background: none; clear: both; overflow: hidden;\">
                        <h4 style=\"margin: 0 0 5px 0;\">Pracownicy:</h4>
                        ";
                        // line 49
                        if (isset($context["employee"])) { $_employee_ = $context["employee"]; } else { $_employee_ = null; }
                        $context['_parent'] = (array) $context;
                        $context['_seq'] = twig_ensure_traversable($this->getAttribute($_employee_, "users"));
                        foreach ($context['_seq'] as $context["_key"] => $context["user"]) {
                            // line 50
                            echo "                        <div style=\"float: left; width: 100px; border: 1px dotted #000; margin-right: 3px; padding: 5px; text-align: center;\">
                            <a href=\"";
                            // line 51
                            if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "employees", "action" => "view", "id" => $this->getAttribute($_user_, "uid")), "admin", true), "html", null, true);
                            echo "\">
                                <img src=\"";
                            // line 52
                            if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                            if ($this->getAttribute($_user_, "photo")) {
                                if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($_user_, "photo"), 0), "url"), "html", null, true);
                            } else {
                                echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
                                echo "/images/admin/no-avatar.png";
                            }
                            echo "\" alt=\"Zdjęcie profilowe\" style=\"width: 100px; height: 80px; overflow: hidden; margin-bottom: 5px; display: block;\" />
                                ";
                            // line 53
                            if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                            echo twig_escape_filter($this->env, $this->getAttribute($_user_, "firstname"), "html", null, true);
                            echo " ";
                            if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                            echo twig_escape_filter($this->env, $this->getAttribute($_user_, "lastname"), "html", null, true);
                            echo "
                            </a>
                        </div>
                        ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
                        $context = array_merge($_parent, array_intersect_key($context, $_parent));
                        // line 57
                        echo "                    </li>
            </ul>
            ";
                    }
                    // line 60
                    echo "            ";
                    if (isset($context["employees"])) { $_employees_ = $context["employees"]; } else { $_employees_ = null; }
                    if (isset($context["employee"])) { $_employee_ = $context["employee"]; } else { $_employee_ = null; }
                    if ($this->getAttribute($_employees_, $this->getAttribute($_employee_, "gid"), array(), "array")) {
                        // line 61
                        echo "            <ul ";
                        if (isset($context["employee"])) { $_employee_ = $context["employee"]; } else { $_employee_ = null; }
                        if ($this->getAttribute($_employee_, "users")) {
                            echo "style=\"margin-top: 0px;\"";
                        }
                        echo ">
            ";
                        // line 62
                        if (isset($context["employee"])) { $_employee_ = $context["employee"]; } else { $_employee_ = null; }
                        if (isset($context["employees"])) { $_employees_ = $context["employees"]; } else { $_employees_ = null; }
                        echo $this->getAttribute($this, "tree", array(0 => $this->getAttribute($_employee_, "gid"), 1 => $_employees_), "method");
                        echo "
            ";
                    }
                    // line 64
                    echo "            </ul>
        </li>
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['employee'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 67
                echo "    ";
            }
        } catch(Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ob_get_clean();
    }

    public function getTemplateName()
    {
        return "employees/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  261 => 67,  253 => 64,  246 => 62,  238 => 61,  233 => 60,  228 => 57,  214 => 53,  203 => 52,  198 => 51,  195 => 50,  190 => 49,  173 => 46,  170 => 45,  165 => 44,  162 => 43,  155 => 42,  150 => 41,  138 => 40,  126 => 72,  121 => 70,  118 => 69,  114 => 38,  110 => 37,  100 => 34,  94 => 33,  89 => 32,  86 => 31,  83 => 30,  73 => 24,  68 => 21,  65 => 20,  52 => 10,  47 => 9,  44 => 8,  35 => 5,  32 => 4,  29 => 3,);
    }
}
