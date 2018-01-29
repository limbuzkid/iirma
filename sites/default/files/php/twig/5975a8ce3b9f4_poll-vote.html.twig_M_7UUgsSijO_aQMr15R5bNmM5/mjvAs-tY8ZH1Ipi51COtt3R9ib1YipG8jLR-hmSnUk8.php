<?php

/* modules/contrib/poll/templates/poll-vote.html.twig */
class __TwigTemplate_8cee9d4a4d9621f852e131807603662d2f19a2e07f8347caf6fa33b5af73ba14 extends Twig_Template
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
        $tags = array("if" => 8);
        $filters = array("without" => 16);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if'),
                array('without'),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setTemplateFile($this->getTemplateName());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 5
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "messages", array()), "html", null, true));
        echo "
<div class=\"poll\">
  <div class=\"vote-form\">
    ";
        // line 8
        if ((isset($context["show_question"]) ? $context["show_question"] : null)) {
            // line 9
            echo "      <h3 class=\"poll-question\">";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["question"]) ? $context["question"] : null), "html", null, true));
            echo "</h3>
    ";
        }
        // line 11
        echo "
    ";
        // line 12
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "choice", array()), "html", null, true));
        echo "

    ";
        // line 14
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "actions", array()), "html", null, true));
        echo "
  </div>
";
        // line 16
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, twig_without((isset($context["form"]) ? $context["form"] : null), "actions", "choice", "messages", "question"), "html", null, true));
        echo "
</div>
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/poll/templates/poll-vote.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 16,  65 => 14,  60 => 12,  57 => 11,  51 => 9,  49 => 8,  43 => 5,);
    }

    public function getSource()
    {
        return "{#
/**
 */
#}
{{ form.messages }}
<div class=\"poll\">
  <div class=\"vote-form\">
    {% if show_question %}
      <h3 class=\"poll-question\">{{ question }}</h3>
    {% endif %}

    {{ form.choice }}

    {{ form.actions }}
  </div>
{{ form|without('actions', 'choice', 'messages', 'question') }}
</div>
";
    }
}
