<?php

/* functions.twig */
class __TwigTemplate_de9af44bf12c56bfad2e6b1af70b7152 extends Twig_Template
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
        // line 10
        echo "    ";
    }

    // line 1
    public function getbreadcrumbs($list = null)
    {
        $context = $this->env->mergeGlobals(array(
            "list" => $list,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 2
            echo "    
    ";
            // line 3
            if (isset($context["list"])) { $_list_ = $context["list"]; } else { $_list_ = null; }
            if ($_list_) {
                // line 4
                echo "        ";
                if (isset($context["list"])) { $_list_ = $context["list"]; } else { $_list_ = null; }
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($_list_);
                $context['loop'] = array(
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                );
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
                    // line 5
                    echo "        <a href=\"";
                    if (isset($context["element"])) { $_element_ = $context["element"]; } else { $_element_ = null; }
                    echo twig_escape_filter($this->env, $this->getAttribute($_element_, "url"), "html", null, true);
                    echo "\" ";
                    if (isset($context["loop"])) { $_loop_ = $context["loop"]; } else { $_loop_ = null; }
                    if ((!$this->getAttribute($_loop_, "first"))) {
                        echo "class=\"odd\"";
                    }
                    echo ">";
                    if (isset($context["element"])) { $_element_ = $context["element"]; } else { $_element_ = null; }
                    echo twig_escape_filter($this->env, $this->getAttribute($_element_, "label"), "html", null, true);
                    echo "</a> ";
                    if (isset($context["loop"])) { $_loop_ = $context["loop"]; } else { $_loop_ = null; }
                    if ((!$this->getAttribute($_loop_, "last"))) {
                        echo "&laquo;";
                    }
                    // line 6
                    echo "        ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['length'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 7
                echo "    ";
            }
            // line 8
            echo "
";
        } catch(Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ob_get_clean();
    }

    public function getTemplateName()
    {
        return "functions.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  91 => 8,  88 => 7,  74 => 6,  38 => 4,  32 => 2,  21 => 1,  17 => 10,  430 => 144,  425 => 139,  420 => 115,  415 => 54,  410 => 16,  397 => 145,  395 => 144,  392 => 143,  387 => 140,  385 => 139,  371 => 131,  366 => 128,  352 => 124,  347 => 123,  341 => 122,  336 => 121,  329 => 116,  327 => 115,  313 => 109,  294 => 108,  287 => 103,  284 => 102,  278 => 98,  269 => 95,  259 => 91,  252 => 90,  245 => 89,  238 => 88,  231 => 87,  225 => 85,  214 => 80,  208 => 78,  197 => 73,  190 => 72,  183 => 71,  177 => 69,  168 => 66,  165 => 65,  162 => 64,  155 => 63,  145 => 55,  142 => 54,  136 => 51,  128 => 46,  124 => 45,  120 => 44,  116 => 43,  112 => 42,  108 => 41,  96 => 32,  89 => 27,  86 => 26,  81 => 24,  77 => 23,  73 => 22,  69 => 21,  61 => 19,  57 => 5,  54 => 17,  52 => 16,  47 => 14,  43 => 13,  39 => 12,  35 => 3,  24 => 2,  22 => 1,  65 => 20,  56 => 22,  50 => 19,  30 => 4,  27 => 3,);
    }
}
