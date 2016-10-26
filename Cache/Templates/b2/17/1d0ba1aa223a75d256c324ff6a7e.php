<?php

/* index/index.twig */
class __TwigTemplate_b2171d0ba1aa223a75d256c324ff6a7e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout.twig");

        $this->blocks = array(
            'breadcrumbs' => array($this, 'block_breadcrumbs'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'javascripts' => array($this, 'block_javascripts'),
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
        $context["breadcrumbsList"] = array();
        // line 5
        echo "
    ";
        // line 6
        if (isset($context["breadcrumbsList"])) { $_breadcrumbsList_ = $context["breadcrumbsList"]; } else { $_breadcrumbsList_ = null; }
        $context["breadcrumbsList"] = twig_array_merge($_breadcrumbsList_, array(0 => array("url" => $this->env->view->getHelper("url")->url(array("controller" => "index"), "admin", true), "label" => "Pulpit")));
        // line 7
        echo "

    ";
        // line 9
        if (isset($context["cms"])) { $_cms_ = $context["cms"]; } else { $_cms_ = null; }
        if (isset($context["breadcrumbsList"])) { $_breadcrumbsList_ = $context["breadcrumbsList"]; } else { $_breadcrumbsList_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_cms_, "breadcrumbs", array(0 => $_breadcrumbsList_), "method"), "html", null, true);
        echo "
";
    }

    // line 12
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 13
        echo "<style type=\"text/css\">
    #portlets { overflow: hidden; }
    .column { width: 33.3%; float: left; padding-bottom: 10px; }
    .portlet { margin: 0 1em 1em 0; border: 1px solid #ccc; background: #fff; }
    .portlet-header { color: #fff; background: #434343; margin: 0.3em; padding: 4px 4px 6px 10px; font-weight: bold; }
    .portlet-header .ui-icon { float: right; }
    .portlet-content { padding: 2px 5px; clear: both; overflow: hidden; }
    .ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
    .ui-sortable-placeholder * { visibility: hidden; }
    
    .portlet-content ul li {
        list-style: none;
        border-bottom: 1px dotted #ddd;
        padding: 8px 5px 10px 5px;
    }
    
    .portlet-content ul li.featured {
        background: #FFFFCC;
    }
    
    .portlet-content ul li h3 {
        margin: 2px 0;
        padding: 0;
        font-size: 12px;
    }
    
    .portlet-content ul li span {
        display: block;
        font-size: 11px;
        color: #808080;
        font-weight: normal;
    }
    
    .portlet-content ul li p {
        margin: 15px 0;
        padding: 0;
        font-size: 11px;
        text-align: justify;
    }
    
    .portlet-content ul li div.info {
        clear: both;
        overflow: hidden;
        font-size: 11px;
        color: #808080;
    }
    
    .portlet-content ul li div.info span {
        display: block;
        float: left;
    }
    
    .portlet-content ul li div.info a {
        display: block;
        float: right;
    }
    
</style>
";
    }

    // line 73
    public function block_javascripts($context, array $blocks = array())
    {
        // line 74
        echo "<script type=\"text/javascript\">
\$(function() {


});
</script>
";
    }

    // line 82
    public function block_content($context, array $blocks = array())
    {
        // line 83
        echo "<div class=\"right-side\">
    <div class=\"content\">
        <div id=\"portlets\">
            <div class=\"column\">
                <div class=\"portlet\" id=\"1_1\">
                    <div class=\"portlet-header\">Aktualności</div>
                    <div class=\"portlet-content\">
                        <ul>
                            ";
        // line 91
        if (isset($context["featured_newses"])) { $_featured_newses_ = $context["featured_newses"]; } else { $_featured_newses_ = null; }
        if (isset($context["newses"])) { $_newses_ = $context["newses"]; } else { $_newses_ = null; }
        if (($_featured_newses_ && $_newses_)) {
            // line 92
            echo "                            ";
            if (isset($context["featured_newses"])) { $_featured_newses_ = $context["featured_newses"]; } else { $_featured_newses_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_featured_newses_);
            foreach ($context['_seq'] as $context["_key"] => $context["news"]) {
                // line 93
                echo "                            <li class=\"featured\">
                                <h3>";
                // line 94
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_news_, "translation"), "title"), "html", null, true);
                echo "</h3>
                                <span>Opublikowano: ";
                // line 95
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_news_, "created_at"), "html", null, true);
                echo "</span>
                                <p>";
                // line 96
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, twig_slice($this->env, strip_tags($this->getAttribute($this->getAttribute($_news_, "translation"), "content")), 0, 250), "html", null, true);
                echo "..</p>
                                <div class=\"info\">
                                    <span>Autor: ";
                // line 98
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_news_, "user"), "firstname"), "html", null, true);
                echo " ";
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_news_, "user"), "lastname"), "html", null, true);
                echo "</span>
                                    <a href=\"";
                // line 99
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "news", "action" => "view", "id" => $this->getAttribute($_news_, "nid")), "admin", true), "html", null, true);
                echo "\">czytaj więcej &raquo;</a>
                                </div>
                            </li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['news'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 102
            echo "  
                            ";
            // line 103
            if (isset($context["newses"])) { $_newses_ = $context["newses"]; } else { $_newses_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_newses_);
            foreach ($context['_seq'] as $context["_key"] => $context["news"]) {
                // line 104
                echo "                            <li>
                                <h3>";
                // line 105
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_news_, "translation"), "title"), "html", null, true);
                echo "</h3>
                                <span>Opublikowano: ";
                // line 106
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_news_, "created_at"), "html", null, true);
                echo "</span>
                                <p>";
                // line 107
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, twig_slice($this->env, strip_tags($this->getAttribute($this->getAttribute($_news_, "translation"), "content")), 0, 250), "html", null, true);
                echo "..</p>
                                <div class=\"info\">
                                    <span>Autor: ";
                // line 109
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_news_, "user"), "firstname"), "html", null, true);
                echo " ";
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_news_, "user"), "lastname"), "html", null, true);
                echo "</span>
                                    <a href=\"";
                // line 110
                if (isset($context["news"])) { $_news_ = $context["news"]; } else { $_news_ = null; }
                echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "news", "action" => "view", "id" => $this->getAttribute($_news_, "nid")), "admin", true), "html", null, true);
                echo "\">czytaj więcej &raquo;</a>
                                </div>
                            </li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['news'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 113
            echo "  
                            ";
        } else {
            // line 115
            echo "                            <li style=\"text-align: center; border: none;\">
                                Brak aktualności
                            </li>
                            ";
        }
        // line 119
        echo "                        </ul>
                        ";
        // line 120
        if (isset($context["featured_newses"])) { $_featured_newses_ = $context["featured_newses"]; } else { $_featured_newses_ = null; }
        if (isset($context["newses"])) { $_newses_ = $context["newses"]; } else { $_newses_ = null; }
        if (($_featured_newses_ && $_newses_)) {
            // line 121
            echo "                        <br />
                        <a href=\"";
            // line 122
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "news"), "admin", true), "html", null, true);
            echo "\" style=\"float: right; margin: 5px;\">Więcej aktualności &raquo;</a>
                        ";
        }
        // line 124
        echo "                    </div>
                </div>
            </div>
            <div class=\"column\">
                <div class=\"portlet\" id=\"2_1\">
                    <div class=\"portlet-header\">Nadchodzące wydarzenia</div>
                    <div class=\"portlet-content\">
                        <ul>
                            ";
        // line 132
        if (isset($context["featured_events"])) { $_featured_events_ = $context["featured_events"]; } else { $_featured_events_ = null; }
        if (isset($context["events"])) { $_events_ = $context["events"]; } else { $_events_ = null; }
        if (($_featured_events_ && $_events_)) {
            // line 133
            echo "                            ";
            if (isset($context["featured_events"])) { $_featured_events_ = $context["featured_events"]; } else { $_featured_events_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_featured_events_);
            foreach ($context['_seq'] as $context["_key"] => $context["event"]) {
                // line 134
                echo "                            <li class=\"featured\">
                                <a href=\"";
                // line 135
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "events", "action" => "view", "id" => $this->getAttribute($_event_, "eid")), "admin", true), "html", null, true);
                echo "\"><h3>";
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_event_, "translation"), "title"), "html", null, true);
                echo "</h3></a>
                                <span>Data: ";
                // line 136
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_event_, "starting_at"), "html", null, true);
                echo " ";
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                if ($this->getAttribute($_event_, "ending_at")) {
                    echo "do ";
                    if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                    echo twig_escape_filter($this->env, $this->getAttribute($_event_, "ending_at"), "html", null, true);
                }
                echo "</span>
                                <div class=\"info\">
                                    <span>Autor: ";
                // line 138
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_event_, "user"), "firstname"), "html", null, true);
                echo " ";
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_event_, "user"), "lastname"), "html", null, true);
                echo "</span>
                                </div>
                            </li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['event'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 141
            echo "  
                            ";
            // line 142
            if (isset($context["events"])) { $_events_ = $context["events"]; } else { $_events_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_events_);
            foreach ($context['_seq'] as $context["_key"] => $context["event"]) {
                // line 143
                echo "                            <li>
                                <a href=\"";
                // line 144
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "events", "action" => "view", "id" => $this->getAttribute($_event_, "eid")), "admin", true), "html", null, true);
                echo "\"><h3>";
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_event_, "translation"), "title"), "html", null, true);
                echo "</h3></a>
                                <span>Data: ";
                // line 145
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_event_, "starting_at"), "html", null, true);
                echo " ";
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                if ($this->getAttribute($_event_, "ending_at")) {
                    echo "do ";
                    if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                    echo twig_escape_filter($this->env, $this->getAttribute($_event_, "ending_at"), "html", null, true);
                }
                echo "</span>
                                <div class=\"info\">
                                    <span>Autor: ";
                // line 147
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_event_, "user"), "firstname"), "html", null, true);
                echo " ";
                if (isset($context["event"])) { $_event_ = $context["event"]; } else { $_event_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_event_, "user"), "lastname"), "html", null, true);
                echo "</span>
                                </div>
                            </li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['event'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 150
            echo "       
                            ";
        } else {
            // line 152
            echo "                            <li style=\"text-align: center; border: none;\">
                                Brak nadchodzących wydarzeń
                            </li>
                            ";
        }
        // line 156
        echo "                        </ul>
                        ";
        // line 157
        if (isset($context["featured_events"])) { $_featured_events_ = $context["featured_events"]; } else { $_featured_events_ = null; }
        if (isset($context["events"])) { $_events_ = $context["events"]; } else { $_events_ = null; }
        if (($_featured_events_ && $_events_)) {
            // line 158
            echo "                        <br />
                        <a href=\"";
            // line 159
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "events"), "admin", true), "html", null, true);
            echo "\" style=\"float: right; margin: 5px;\">Więcej wydarzeń &raquo;</a>
                        ";
        }
        // line 161
        echo "                    </div>
                </div>
            </div>
            <div class=\"column\">
                <div class=\"portlet\" id=\"3_1\">
                    <div class=\"portlet-header\">Ostatio edytowane dokumenty</div>
                    <div class=\"portlet-content\">
                        <ul>
                            ";
        // line 169
        if (isset($context["documents"])) { $_documents_ = $context["documents"]; } else { $_documents_ = null; }
        if ($_documents_) {
            // line 170
            echo "                            ";
            if (isset($context["documents"])) { $_documents_ = $context["documents"]; } else { $_documents_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_documents_);
            foreach ($context['_seq'] as $context["_key"] => $context["document"]) {
                // line 171
                echo "                            <li>
                                <a href=\"";
                // line 172
                if (isset($context["document"])) { $_document_ = $context["document"]; } else { $_document_ = null; }
                echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "documents", "action" => "view", "id" => $this->getAttribute($_document_, "did")), "admin", true), "html", null, true);
                echo "\"><h3>";
                if (isset($context["document"])) { $_document_ = $context["document"]; } else { $_document_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_document_, "name"), "html", null, true);
                echo "</h3></a>
                                <span>Autor: ";
                // line 173
                if (isset($context["document"])) { $_document_ = $context["document"]; } else { $_document_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_document_, "user"), "firstname"), "html", null, true);
                echo " ";
                if (isset($context["document"])) { $_document_ = $context["document"]; } else { $_document_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_document_, "user"), "lastname"), "html", null, true);
                echo " &nbsp;&nbsp; Data modyfikacji: ";
                if (isset($context["document"])) { $_document_ = $context["document"]; } else { $_document_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_document_, "modified_at"), "html", null, true);
                echo "</span>
                            </li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['document'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 176
            echo "                            ";
        } else {
            // line 177
            echo "                            <li style=\"text-align: center; border: none;\">
                                Brak ostatnio edytowanych dokumentów
                            </li>
                            ";
        }
        // line 181
        echo "                        </ul>
                    </div>
                </div>
                <div class=\"portlet\" id=\"3_2\">
                    <div class=\"portlet-header\">Ostatio zalogowani użytkownicy</div>
                    <div class=\"portlet-content\">
                        <ul> 
                            ";
        // line 188
        if (isset($context["users"])) { $_users_ = $context["users"]; } else { $_users_ = null; }
        if ($_users_) {
            // line 189
            echo "                            ";
            if (isset($context["users"])) { $_users_ = $context["users"]; } else { $_users_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_users_);
            foreach ($context['_seq'] as $context["_key"] => $context["user"]) {
                // line 190
                echo "                            <li style=\"float: left; width: 100px; height: 115px; margin: 3px; border: 1px dotted #ccc; text-align: center;\">
                                <a href=\"";
                // line 191
                if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "employees", "action" => "view", "id" => $this->getAttribute($_user_, "uid")), "admin", true), "html", null, true);
                echo "\">
                                    <img src=\"";
                // line 192
                if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                if ($this->getAttribute($_user_, "photo")) {
                    if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($_user_, "photo"), 0), "url"), "html", null, true);
                } else {
                    echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
                    echo "/images/admin/no-avatar.png";
                }
                echo "\" alt=\"\" style=\"width: 100px; height: 100px;\" />
                                    ";
                // line 193
                if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_user_, "firstname"), "html", null, true);
                echo " ";
                if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_user_, "lastname"), "html", null, true);
                echo "
                                </a>
                            </li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 196
            echo " 
                            ";
        } else {
            // line 198
            echo "                            <li style=\"text-align: center; border: none;\">
                                Brak ostatnio zalogowanych użytkowników
                            </li>
                            ";
        }
        // line 202
        echo "                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
";
    }

    public function getTemplateName()
    {
        return "index/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  508 => 202,  502 => 198,  498 => 196,  484 => 193,  473 => 192,  468 => 191,  465 => 190,  459 => 189,  456 => 188,  447 => 181,  441 => 177,  438 => 176,  422 => 173,  414 => 172,  411 => 171,  405 => 170,  402 => 169,  392 => 161,  387 => 159,  384 => 158,  380 => 157,  377 => 156,  371 => 152,  367 => 150,  353 => 147,  340 => 145,  332 => 144,  329 => 143,  324 => 142,  321 => 141,  307 => 138,  294 => 136,  286 => 135,  283 => 134,  277 => 133,  273 => 132,  263 => 124,  258 => 122,  255 => 121,  251 => 120,  248 => 119,  242 => 115,  238 => 113,  227 => 110,  219 => 109,  213 => 107,  208 => 106,  203 => 105,  200 => 104,  195 => 103,  192 => 102,  181 => 99,  173 => 98,  167 => 96,  162 => 95,  157 => 94,  154 => 93,  148 => 92,  144 => 91,  134 => 83,  131 => 82,  121 => 74,  118 => 73,  56 => 13,  53 => 12,  45 => 9,  41 => 7,  38 => 6,  35 => 5,  32 => 4,  29 => 3,);
    }
}
