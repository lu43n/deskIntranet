<?php

/* pm/index.twig */
class __TwigTemplate_851c6bb3c1d205a88bcc02366886d17f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout.twig");

        $this->blocks = array(
            'breadcrumbs' => array($this, 'block_breadcrumbs'),
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
        $context["breadcrumbsList"] = array(0 => array("url" => $this->env->view->getHelper("url")->url(array("controller" => "pm"), "admin", true), "label" => "Wiadomości prywatne"));
        // line 5
        echo "    ";
        if (isset($context["cms"])) { $_cms_ = $context["cms"]; } else { $_cms_ = null; }
        if (isset($context["breadcrumbsList"])) { $_breadcrumbsList_ = $context["breadcrumbsList"]; } else { $_breadcrumbsList_ = null; }
        echo twig_escape_filter($this->env, $this->getAttribute($_cms_, "breadcrumbs", array(0 => $_breadcrumbsList_), "method"), "html", null, true);
        echo "
";
    }

    // line 8
    public function block_helpbox($context, array $blocks = array())
    {
        // line 9
        echo "    ";
        $this->env->loadTemplate("pm/_helpBox.twig")->display($context);
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        echo "      

<script type=\"text/javascript\" src=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/jquery.datatables.js\"></script>
<link rel=\"stylesheet\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/css/admin/jquery.datatables.css\" type=\"text/css\" />

<style type=\"text/css\">
    #pmTableInbox tr.undreaded td {
        font-weight: bold;
        }
</style>

<div class=\"right-side\">
    <div class=\"content\">
        <h1>Lista wiadomości prywatnych:</h1>
        
        ";
        // line 27
        if (isset($context["flashMessages"])) { $_flashMessages_ = $context["flashMessages"]; } else { $_flashMessages_ = null; }
        if ($_flashMessages_) {
            // line 28
            echo "        <div class=\"messages\">
            ";
            // line 29
            if (isset($context["flashMessages"])) { $_flashMessages_ = $context["flashMessages"]; } else { $_flashMessages_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_flashMessages_);
            foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                // line 30
                echo "                <div class=\"message ";
                if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_message_, "type"), "html", null, true);
                echo "\">
                    ";
                // line 31
                if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_message_, "message"), "html", null, true);
                echo "
                </div> 
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 34
            echo "        </div>
        ";
        }
        // line 35
        echo "   

        <table id=\"pmTableInbox\" class=\"dataTable\">
            <thead>
                <tr>
                    <th style=\"width: 15px;\"></th>
                    <th>Od</th>
                    <th>Do</th>
                    <th>Temat</th>
                    <th>Data otrzymania</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
            ";
        // line 49
        if (isset($context["messages"])) { $_messages_ = $context["messages"]; } else { $_messages_ = null; }
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($_messages_);
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
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 50
            echo "                <tr id=\"message_";
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_message_, "pmid"), "html", null, true);
            echo "\" class=\"";
            if (isset($context["loop"])) { $_loop_ = $context["loop"]; } else { $_loop_ = null; }
            echo twig_escape_filter($this->env, twig_cycle(array(0 => "", 1 => "odd"), $this->getAttribute($_loop_, "index0")), "html", null, true);
            echo " ";
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            if ($this->getAttribute($_message_, "unreaded")) {
                echo "undreaded";
            }
            echo "\">
                    <td class=\"center\"><input type=\"checkbox\" name=\"messages[]\" value=\"";
            // line 51
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_message_, "pmid"), "html", null, true);
            echo "\" /></td>
                    <td><a href=\"";
            // line 52
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "view", "id" => $this->getAttribute($_message_, "pmid")), "admin", true), "html", null, true);
            echo "\">";
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_message_, "sender_name"), "html", null, true);
            echo "</a></td>
                    <td><a href=\"";
            // line 53
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "view", "id" => $this->getAttribute($_message_, "pmid")), "admin", true), "html", null, true);
            echo "\">";
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_message_, "recipient_name"), "html", null, true);
            echo "</a></td>
                    <td><a href=\"";
            // line 54
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "view", "id" => $this->getAttribute($_message_, "pmid")), "admin", true), "html", null, true);
            echo "\">";
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_message_, "subject"), "html", null, true);
            echo " ";
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            if ((twig_length_filter($this->env, $this->getAttribute($_message_, "replies")) != 0)) {
                echo " (";
                if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
                echo twig_escape_filter($this->env, twig_length_filter($this->env, $this->getAttribute($_message_, "replies")), "html", null, true);
                echo ") ";
            }
            echo " ";
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            if ($this->getAttribute($_message_, "has_attachments")) {
                echo "<img src=\"";
                if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
                echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
                echo "/images/admin/attachments_small.png\" alt=\"Załączniki\" style=\"float: right;\" />";
            }
            echo "</a></td>
                    <td><a href=\"";
            // line 55
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "view", "id" => $this->getAttribute($_message_, "pmid")), "admin", true), "html", null, true);
            echo "\">";
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_message_, "sent_at"), "html", null, true);
            echo "</a></td>
                    <td>
                        <a href=\"";
            // line 57
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "view", "id" => $this->getAttribute($_message_, "pmid")), "admin", true), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/view_small.png\" alt=\"Edytuj\" class=\"icon\" /> Pokaż</a> | 
                        <a href=\"#delete\" name=\"delete-message\" rel=\"";
            // line 58
            if (isset($context["message"])) { $_message_ = $context["message"]; } else { $_message_ = null; }
            echo twig_escape_filter($this->env, $this->getAttribute($_message_, "pmid"), "html", null, true);
            echo "\"><img src=\"";
            if (isset($context["baseUrl"])) { $_baseUrl_ = $context["baseUrl"]; } else { $_baseUrl_ = null; }
            echo twig_escape_filter($this->env, $_baseUrl_, "html", null, true);
            echo "/images/admin/delete_small.png\" alt=\"Usuń\" class=\"icon\" /> Usuń</a>
                    </td>
                </tr> 
            ";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 62
        echo "            </tbody>
        </table>
        <script type=\"text/javascript\">
        // <[[!CDATA 
            \$('#pmTableInbox').dataTable({ 
                \"bJQueryUI\": true, 
                \"sPaginationType\": \"full_numbers\", 
                \"oLanguage\": { 
                    \"sUrl\": \"";
        // line 70
        echo twig_escape_filter($this->env, $this->env->view->getHelper("baseUrl")->baseUrl(), "html", null, true);
        echo "/js/admin/datatables/pl.txt\" 
                },
                \"aaSorting\": [[ 4, \"desc\" ]],
                \"aoColumns\": [
                    { \"bSortable\": false },
                    null,
                    null,
                    null,
                    null,
                    { \"bSortable\": false }
                ]
            });
        // ]]>
        </script>

        <script type=\"text/javascript\">
        // <[[!CDATA
            \$(document).ready(function(){

                // Forms JavaScript

                var deleteMessagesButton = \$('#delete-messages');

                \$('#delete-messages').click(function () {

                    var actionUrl = \"";
        // line 95
        echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "delete"), "admin", true), "html", null, true);
        echo "\";
                    var params    = { id: \$('input[name=\"messages[]\"]').serializeArray() };
                    var list      = { item: '#message_', items: '#pmTableInbox' }

                    \$.ajax({
                        url: actionUrl,
                        type: \"POST\",
                        data: params,
                        dataType: \"json\",
                        success: function (data) {
                            if(data != null)
                            {     
                                setNotification(data.message, data.type);

                                if(data.id)
                                {
                                    \$.each(data.id, function (key, val) {
                                        \$(list.item + val).remove();
                                    });

                                    if(\$(list.items + ' tbody tr').size() == 0)
                                    {
                                        \$(list.items).dataTable().fnClearTable();
                                    }
                                }
                            }

                            \$(list.items + \"tbody\").children('li').each(function(index, value) {
                                \$(this).removeClass('odd');

                                if(index % 2)
                                {
                                    \$(this).addClass('odd'); 
                                }
                            }); 
                            
                            if(\$('input[name=\"messages[]\"]:checked').length == 0)
                            {
                                deleteMessagesButton.hide();
                            }
                            else
                            {
                                deleteMessagesButton.show();
                            }
                        }
                    });
                    

                    return false;
                });

                \$('a[name=\"delete-message\"]').click(function () {

                    var actionUrl = \"";
        // line 148
        echo twig_escape_filter($this->env, $this->env->view->getHelper("url")->url(array("controller" => "pm", "action" => "delete"), "admin", true), "html", null, true);
        echo "\";
                    var params    = { id: { 0: { value: \$(this).attr('rel') } } };
                    var list      = { item: '#message_', items: '#pmTableInbox' }

                    \$.ajax({
                        url: actionUrl,
                        type: \"POST\",
                        data: params,
                        dataType: \"json\",
                        success: function (data) {
                            if(data != null)
                            {     
                                setNotification(data.message, data.type);

                                if(data.id)
                                {
                                    \$.each(data.id, function (key, val) {
                                        \$(list.item + val).remove();
                                    });

                                    if(\$(list.items + ' tbody tr').size() == 0)
                                    {
                                        \$(list.items).dataTable().fnClearTable();
                                    }
                                }
                            }

                            \$(list.items + \"tbody\").children('li').each(function(index, value) {
                                \$(this).removeClass('odd');

                                if(index % 2)
                                {
                                    \$(this).addClass('odd'); 
                                }
                            }); 
                            
                            if(\$('input[name=\"messages[]\"]:checked').length == 0)
                            {
                                deleteMessagesButton.hide();
                            }
                            else
                            {
                                deleteMessagesButton.show();
                            }
                        }
                    });
                    
                    return false;
                });

                \$('input[name=\"messages[]\"]').change(function(){
                    if(\$('input[name=\"messages[]\"]:checked').length == 0)
                    {
                        deleteMessagesButton.hide();
                    }
                    else
                    {
                        deleteMessagesButton.show();
                    }
                });

                \$('button[name=\"select-messages\"]').click(function () {      
                    cms.toggleSelect('input[name=\"messages[]\"]');

                    if(\$('input[name=\"messages[]\"]:checked').length == 0)
                    {
                        deleteMessagesButton.hide();
                    }
                    else
                    {
                        deleteMessagesButton.show();
                    }
                });      
        
            });
        // ]]>
        </script>
    </div>
</div>
 


";
    }

    public function getTemplateName()
    {
        return "pm/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  333 => 148,  277 => 95,  249 => 70,  239 => 62,  217 => 58,  209 => 57,  200 => 55,  176 => 54,  168 => 53,  160 => 52,  155 => 51,  141 => 50,  123 => 49,  107 => 35,  103 => 34,  93 => 31,  87 => 30,  82 => 29,  79 => 28,  76 => 27,  61 => 15,  57 => 14,  51 => 12,  46 => 9,  43 => 8,  34 => 5,  31 => 4,  28 => 3,);
    }
}
