<?php

/* themes/basic/templates/node.html.twig */
class __TwigTemplate_c8d73bd54b65b128d277cf0cacf7fed7c62dc31c3b82e8aa547656e266a6fc61 extends Twig_Template
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
        $tags = array("set" => 2, "if" => 18, "trans" => 31);
        $filters = array("t" => 37, "without" => 43);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('set', 'if', 'trans'),
                array('t', 'without'),
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

        // line 2
        $context["classes"] = array(0 => "node");
        // line 5
        echo "
";
        // line 16
        echo "<article";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => (isset($context["classes"]) ? $context["classes"] : null)), "method"), "html", null, true));
        echo ">

  ";
        // line 18
        if ((((((isset($context["title_prefix"]) ? $context["title_prefix"] : null) || (isset($context["title_suffix"]) ? $context["title_suffix"] : null)) || (isset($context["display_submitted"]) ? $context["display_submitted"] : null)) || (isset($context["unpublished"]) ? $context["unpublished"] : null)) || (twig_test_empty((isset($context["page"]) ? $context["page"] : null)) && (isset($context["label"]) ? $context["label"] : null)))) {
            // line 19
            echo "    <div>
      ";
            // line 20
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["title_prefix"]) ? $context["title_prefix"] : null), "html", null, true));
            echo "
      ";
            // line 21
            if (( !(isset($context["page"]) ? $context["page"] : null) && (isset($context["label"]) ? $context["label"] : null))) {
                // line 22
                echo "        <h2";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["title_attributes"]) ? $context["title_attributes"] : null), "addClass", array(0 => (isset($context["title_classes"]) ? $context["title_classes"] : null)), "method"), "html", null, true));
                echo ">
          <a href=\"";
                // line 23
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["url"]) ? $context["url"] : null), "html", null, true));
                echo "\" rel=\"bookmark\">";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["label"]) ? $context["label"] : null), "html", null, true));
                echo "</a>
        </h2>
      ";
            }
            // line 26
            echo "      ";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["title_suffix"]) ? $context["title_suffix"] : null), "html", null, true));
            echo "

      ";
            // line 28
            if ((isset($context["display_submitted"]) ? $context["display_submitted"] : null)) {
                // line 29
                echo "        <div class=\"submitted\">
          ";
                // line 30
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["author_picture"]) ? $context["author_picture"] : null), "html", null, true));
                echo "
          ";
                // line 31
                echo t("Submitted by @author_name on @date", array("@author_name" => (isset($context["author_name"]) ? $context["author_name"] : null), "@date" => (isset($context["date"]) ? $context["date"] : null), ));
                // line 32
                echo "          ";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["metadata"]) ? $context["metadata"] : null), "html", null, true));
                echo "
        </div>
      ";
            }
            // line 35
            echo "
      ";
            // line 36
            if ( !$this->getAttribute((isset($context["node"]) ? $context["node"] : null), "published", array())) {
                // line 37
                echo "        <p class=\"node--unpublished\">";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Unpublished")));
                echo "</p>
      ";
            }
            // line 39
            echo "    </div>
  ";
        }
        // line 41
        echo "
  <div";
        // line 42
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["content_attributes"]) ? $context["content_attributes"] : null), "addClass", array(0 => "content"), "method"), "html", null, true));
        echo ">
    ";
        // line 43
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, twig_without((isset($context["content"]) ? $context["content"] : null), "links"), "html", null, true));
        echo "
  </div><!-- /.content -->

  ";
        // line 46
        if ($this->getAttribute((isset($context["content"]) ? $context["content"] : null), "links", array())) {
            // line 47
            echo "    <div class=\"links\">
      ";
            // line 48
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["content"]) ? $context["content"] : null), "links", array()), "html", null, true));
            echo "
    </div><!-- /.links -->
  ";
        }
        // line 51
        echo "
</article><!-- /.node -->
";
    }

    public function getTemplateName()
    {
        return "themes/basic/templates/node.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  141 => 51,  135 => 48,  132 => 47,  130 => 46,  124 => 43,  120 => 42,  117 => 41,  113 => 39,  107 => 37,  105 => 36,  102 => 35,  95 => 32,  93 => 31,  89 => 30,  86 => 29,  84 => 28,  78 => 26,  70 => 23,  65 => 22,  63 => 21,  59 => 20,  56 => 19,  54 => 18,  48 => 16,  45 => 5,  43 => 2,);
    }

    public function getSource()
    {
        return "{# Create classes array. The 'node' class is required for contextual edit links. #}
{% set classes = [
  'node'
] %}

{# BEM inspired class syntax: https://en.bem.info/
   Enable this code if you would like node classes like \"article article--layout-teaser\", where article is the content type and teaser is the view mode.
{% set classes = classes|merge([
  node.bundle|clean_class,
  view_mode ? node.bundle|clean_class ~ '--layout-' ~ view_mode|clean_class
]) %}
{% set title_classes = [
  node.bundle|clean_class ~ '__title'
] %}
#}
<article{{ attributes.addClass(classes) }}>

  {% if title_prefix or title_suffix or display_submitted or unpublished or page is empty and label %}
    <div>
      {{ title_prefix }}
      {% if not page and label %}
        <h2{{ title_attributes.addClass(title_classes) }}>
          <a href=\"{{ url }}\" rel=\"bookmark\">{{ label }}</a>
        </h2>
      {% endif %}
      {{ title_suffix }}

      {% if display_submitted %}
        <div class=\"submitted\">
          {{ author_picture }}
          {% trans %}Submitted by {{ author_name }} on {{ date }}{% endtrans %}
          {{ metadata }}
        </div>
      {% endif %}

      {% if not node.published %}
        <p class=\"node--unpublished\">{{ 'Unpublished'|t }}</p>
      {% endif %}
    </div>
  {% endif %}

  <div{{ content_attributes.addClass('content') }}>
    {{ content|without('links') }}
  </div><!-- /.content -->

  {% if content.links %}
    <div class=\"links\">
      {{ content.links }}
    </div><!-- /.links -->
  {% endif %}

</article><!-- /.node -->
";
    }
}
