<?php

/* themes/basic/templates/field.html.twig */
class __TwigTemplate_3d4a8dbc34026143494b59cd3202a3a2e830459e6c2df7fa83f39b68acd22d19 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@stable/field/field.html.twig", "themes/basic/templates/field.html.twig", 1);
        $this->blocks = array(
        );
    }

    protected function doGetParent(array $context)
    {
        return "@stable/field/field.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $tags = array("set" => 4);
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('set'),
                array(),
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

        // line 4
        $context["classes"] = array();
        // line 13
        $context["attributes"] = $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => (isset($context["classes"]) ? $context["classes"] : null)), "method");
        // line 19
        $context["title_attributes"] = $this->getAttribute((isset($context["title_attributes"]) ? $context["title_attributes"] : null), "addClass", array(0 => ((((isset($context["label_display"]) ? $context["label_display"] : null) == "visually_hidden")) ? ("visually-hidden") : (""))), "method");
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    public function getTemplateName()
    {
        return "themes/basic/templates/field.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  54 => 1,  52 => 19,  50 => 13,  48 => 4,  11 => 1,);
    }

    public function getSource()
    {
        return "{% extends \"@stable/field/field.html.twig\" %}

{# Create classes array #}
{% set classes = [] %}

{# BEM inspired class syntax: https://en.bem.info/
   Enable this code if you would like field classes like \"article__tags\", where article is the content type and field_tags is the field name.
{% set classes = classes|merge([
  bundle ~ '__' ~ field_name|replace({'field_' : ''})|clean_class
]) %}
#}

{% set attributes = attributes.addClass(classes) %}

{#
  Ensures that the visually hidden option for field labels works correctly.
  @todo: Remove when https://www.drupal.org/node/2779919 is resolved.
#}
{% set title_attributes = title_attributes.addClass(label_display == 'visually_hidden' ? 'visually-hidden') %}
";
    }
}
