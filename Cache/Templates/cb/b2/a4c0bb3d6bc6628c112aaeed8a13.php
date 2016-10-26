<?php

/* layout.twig */
class __TwigTemplate_cbb2a4c0bb3d6bc6628c112aaeed8a13 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
            'javascripts' => array($this, 'block_javascripts'),
            'helpbox' => array($this, 'block_helpbox'),
            'breadcrumbs' => array($this, 'block_breadcrumbs'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $context["cms"] = $this->env->loadTemplate("functions.twig");
        // line 2
        echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"pl\" lang=\"pl\">
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <meta http-equiv=\"Content-Language\" content=\"pl\" />

    <title>Panel Administracyjny - IntraNet</title>

    <link rel=\"stylesheet\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/css/admin/jquery.ui.css\" type=\"text/css\" />
    <link rel=\"stylesheet\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/css/admin/jquery.treetable.css\" type=\"text/css\" />
    <link rel=\"stylesheet\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/css/admin/style.css\" type=\"text/css\" />
    <link rel=\"stylesheet\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/css/admin/forms.css\" type=\"text/css\" />
   
    ";
        // line 16
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 17
        echo "
    <script type=\"text/javascript\" src=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/jquery.base.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/jquery.ui.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/jquery.cookie.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/jquery.treetable.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/jquery.idle.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/cms.scripts.js\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 24
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/cms.permissions.js\"></script>

    ";
        // line 26
        if (isset($context["acl"])) { $_acl_ = $context["acl"]; } else { $_acl_ = null; }
        if ($this->getAttribute($_acl_, "isUserAllowed", array(0 => "intranet"), "method")) {
            // line 27
            echo "    <script type=\"text/javascript\">
    // <[[!CDATA
        function keepAlive ()
        {
            \$.ajax({
                url: \"";
            // line 32
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "index", "action" => "keepAlive"), "admin", true), "html", null, true);
            echo "\",
                type: \"GET\",
                dataType: \"html\"
            })
        }
        
        setInterval(\"keepAlive()\", 180000);

        var chatConfig = {
            'openChat': '";
            // line 41
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "chat", "action" => "ajax-open-chat"), "admin", true), "html", null, true);
            echo "',
            'setUserActivity': '";
            // line 42
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "chat", "action" => "ajax-set-user-activity"), "admin", true), "html", null, true);
            echo "',
            'refreshUsersList': '";
            // line 43
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "chat", "action" => "ajax-refresh-users-list"), "admin", true), "html", null, true);
            echo "',
            'sendMessage': '";
            // line 44
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "chat", "action" => "ajax-send-message"), "admin", true), "html", null, true);
            echo "',
            'refreshChats': '";
            // line 45
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "chat", "action" => "ajax-refresh-chats"), "admin", true), "html", null, true);
            echo "',
            'getInitChatBoxes': '";
            // line 46
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "chat", "action" => "ajax-get-init-chat-boxes"), "admin", true), "html", null, true);
            echo "',
        };
    // ]]>
    </script>

    <script type=\"text/javascript\" src=\"";
            // line 51
            echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
            echo "/js/admin/cms.chat.js\"></script>

    ";
        }
        // line 54
        echo "    ";
        $this->displayBlock('javascripts', $context, $blocks);
        // line 55
        echo "</head>

<body>
    <div id=\"throbber\"></div>
    <div id=\"notification\"></div>

    <div id=\"wrap\">
        <div id=\"top\">
                <a href=\"";
        // line 63
        echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "index", "action" => "index"), "admin", true), "html", null, true);
        echo "\" class=\"logo\"><img src=\"";
        if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
        echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
        echo "/images/admin/panel_logo.png\" alt=\"deskCMS\" /></a>
                ";
        // line 64
        if (isset($context["acl"])) { $_acl_ = $context["acl"]; } else { $_acl_ = null; }
        if ($this->getAttribute($_acl_, "isUserAllowed", array(0 => "intranet"), "method")) {
            // line 65
            echo "                <ul>
                    <li><a href=\"";
            // line 66
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "index", "action" => "index"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_home.gif\" alt=\"Pulpit\" /> Pulpit</a></li>
                    <li class=\"separator\"></li>
                    <li class=\"dropdown\">
                        <span><img src=\"";
            // line 69
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_data.png\" alt=\"Moduły\" class=\"icon\" /> Zasoby</span>
                        <ul>
                            <li><a href=\"";
            // line 71
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "documents"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_documents.png\" alt=\"Dokumenty\" class=\"icon\" /> Dokumenty firmowe</a></li>
                            <li><a href=\"";
            // line 72
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "news"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_news.png\" alt=\"Aktualności\" class=\"icon\" /> Aktualności</a></li>
                            <li><a href=\"";
            // line 73
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "events"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_events.png\" alt=\"Kalendarz wydarzeń\" class=\"icon\" /> Kalendarz wydarzeń</a></li>
                        </ul>
                    </li>
                    <li class=\"separator\"></li>
                    <li class=\"dropdown\">
                        <span><img src=\"";
            // line 78
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_structure.png\" alt=\"Moduły\" class=\"icon\" /> Struktura</span>
                        <ul>
                            <li><a href=\"";
            // line 80
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "employees"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_employees.png\" alt=\"Struktura firmy\" class=\"icon\" /> Struktura firmy</a></li>
                        </ul>
                    </li>
                    <li class=\"separator\"></li>
                    <li class=\"dropdown\">
                        <span><img src=\"";
            // line 85
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_administration.png\" alt=\"Administracja\" class=\"icon\" /> Administracja</span>
                        <ul>
                            <li><a href=\"";
            // line 87
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "forms"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/forms_small.png\" style=\"position: relative; top: 5px;\" alt=\"Formularze\" /> Formularze</a></li>
                            <li><a href=\"";
            // line 88
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "users"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/group_small.png\" style=\"position: relative; top: 5px;\" alt=\"Użytkownicy\" /> Użytkownicy</a></li>
                            <li><a href=\"";
            // line 89
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "dictionary"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/dictionary_small.png\" style=\"position: relative; top: 5px;\" alt=\"Słownik\" /> Słownik</a></li>
                            <li><a href=\"";
            // line 90
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "permissions"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_permissions.png\" style=\"position: relative; top: 5px;\" alt=\"Uprawnienia\" /> Uprawnienia</a></li>
                            <li><a href=\"";
            // line 91
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "settings"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_settings.gif\" alt=\"Ustawienia\" /> Ustawienia</a></li>
                        </ul>
                    </li>
                    <li class=\"separator\"></li>
                    <li><a href=\"";
            // line 95
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "support"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_menu_help.gif\" alt=\"Pomoc\" /> Pomoc</a></li>
                </ul>
                ";
        }
        // line 98
        echo "        </div>

        <div id=\"main\">

            ";
        // line 102
        if (isset($context["acl"])) { $_acl_ = $context["acl"]; } else { $_acl_ = null; }
        if ($this->getAttribute($_acl_, "isUserAllowed", array(0 => "intranet"), "method")) {
            // line 103
            echo "            <div class=\"left-side\">
                <div class=\"help_box\">
                    
                    <div class=\"logininfo\">
                        Zalogowany jako:<br/>
                        <a href=\"";
            // line 108
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "profile"), "admin", true), "html", null, true);
            echo "\" class=\"login\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/panel_user.gif\" alt=\"Profil: ";
            if (isset($context["identity"])) { $_identity_ = $context["identity"]; } else { $_identity_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_identity_, "firstname"), "html", null, true);
            echo " ";
            if (isset($context["identity"])) { $_identity_ = $context["identity"]; } else { $_identity_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_identity_, "lastname"), "html", null, true);
            echo "\" /> ";
            if (isset($context["identity"])) { $_identity_ = $context["identity"]; } else { $_identity_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_identity_, "firstname"), "html", null, true);
            echo " ";
            if (isset($context["identity"])) { $_identity_ = $context["identity"]; } else { $_identity_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_identity_, "lastname"), "html", null, true);
            echo "</a>
                        <a href=\"";
            // line 109
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm"), "admin", true), "html", null, true);
            echo "\">Wiadmości (";
            if (isset($context["newPmMessages"])) { $_newPmMessages_ = $context["newPmMessages"]; } else { $_newPmMessages_ = null; }
            echo twig_escape_filter($this->env, ((array_key_exists("newPmMessages", $context)) ? (_twig_default_filter($_newPmMessages_, 0)) : (0)), "html", null, true);
            echo ")</a> | <a href=\"";
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "login", "action" => "logout"), "admin", true), "html", null, true);
            echo "\">Wyloguj</a>
                    </div>

                </div>
                <div class=\"help_box_end\"></div>

                ";
            // line 115
            $this->displayBlock('helpbox', $context, $blocks);
            // line 116
            echo "
                <div class=\"help_box_start\"></div>
                <div class=\"help_box\" id=\"chatsList\">
                    <h2 style=\"padding-top: 0;\">Czat</h2>
                    <ul class=\"list\">
                        ";
            // line 121
            if (isset($context["usersList"])) { $_usersList_ = $context["usersList"]; } else { $_usersList_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_usersList_);
            foreach ($context['_seq'] as $context["_key"] => $context["user"]) {
                // line 122
                echo "                        <li class=\"";
                if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_user_, "state"), "html", null, true);
                echo "\">
                            <a href=\"#\" data-action=\"open\" data-recipientid=\"";
                // line 123
                if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_user_, "uid"), "html", null, true);
                echo "\">
                                <span>";
                // line 124
                if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_user_, "firstname"), "html", null, true);
                echo " ";
                if (isset($context["user"])) { $_user_ = $context["user"]; } else { $_user_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_user_, "lastname"), "html", null, true);
                echo "</span>
                            </a>
                        </li>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 128
            echo "                    </ul>
                    <ul>
                        <li><hr style=\"border: none; border-top: 1px dotted #ddd; width: 90%;\" /></li>
                        <li><a href=\"";
            // line 131
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "chat"), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/all_chats.png\" alt=\"Wszystkie wiadomości\" />  Wszystkie konwersacje</a></li>
                    </ul>
                </div>
                <div class=\"help_box_end\"></div> 

            </div>
            
            <div class=\"right-side breadcrumbs\">
                ";
            // line 139
            $this->displayBlock('breadcrumbs', $context, $blocks);
            // line 140
            echo "            </div>
            
            ";
        }
        // line 143
        echo "            
            ";
        // line 144
        $this->displayBlock('content', $context, $blocks);
        // line 145
        echo "
            <div id=\"chatBoxes\" class=\"right-side\"></div>

        </div>

    </div>

</body>
</html>
";
    }

    // line 16
    public function block_stylesheets($context, array $blocks = array())
    {
    }

    // line 54
    public function block_javascripts($context, array $blocks = array())
    {
    }

    // line 115
    public function block_helpbox($context, array $blocks = array())
    {
    }

    // line 139
    public function block_breadcrumbs($context, array $blocks = array())
    {
    }

    // line 144
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  430 => 144,  425 => 139,  420 => 115,  415 => 54,  410 => 16,  397 => 145,  395 => 144,  392 => 143,  387 => 140,  385 => 139,  371 => 131,  366 => 128,  352 => 124,  347 => 123,  341 => 122,  336 => 121,  329 => 116,  327 => 115,  313 => 109,  294 => 108,  287 => 103,  284 => 102,  278 => 98,  269 => 95,  259 => 91,  252 => 90,  245 => 89,  238 => 88,  231 => 87,  225 => 85,  214 => 80,  208 => 78,  197 => 73,  190 => 72,  183 => 71,  177 => 69,  168 => 66,  165 => 65,  162 => 64,  155 => 63,  145 => 55,  142 => 54,  136 => 51,  128 => 46,  124 => 45,  120 => 44,  116 => 43,  112 => 42,  108 => 41,  96 => 32,  89 => 27,  86 => 26,  81 => 24,  77 => 23,  73 => 22,  69 => 21,  61 => 19,  57 => 18,  54 => 17,  52 => 16,  47 => 14,  43 => 13,  39 => 12,  35 => 11,  24 => 2,  22 => 1,  65 => 20,  56 => 22,  50 => 19,  30 => 4,  27 => 3,);
    }
}
