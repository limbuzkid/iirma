<?php

/* modules/contrib/poll/templates/poll-meter.html.twig */
class __TwigTemplate_3be47385c8f93cdb1d51d5664beb70b767ee1824751cc2efe58657b2db21bec2 extends Twig_Template
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
        $tags = array("if" => 32);
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if'),
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

        // line 26
        echo "<dt class=\"choice-title\">";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["choice"]) ? $context["choice"] : null), "html", null, true));
        echo "</dt>
<dd class=\"choice-result\">
  <div";
        // line 28
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["attributes"]) ? $context["attributes"] : null), "html", null, true));
        echo ">
    <div style=\"width: ";
        // line 29
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["percentage"]) ? $context["percentage"] : null), "html", null, true));
        echo "%\" class=\"foreground\"></div>
  </div>

  ";
        // line 32
        if ((isset($context["display_value"]) ? $context["display_value"] : null)) {
            // line 33
            echo "    <div class=\"percent\">";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["display_value"]) ? $context["display_value"] : null), "html", null, true));
            echo "</div>
  ";
        }
        // line 35
        echo "</dd>
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/poll/templates/poll-meter.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  67 => 35,  61 => 33,  59 => 32,  53 => 29,  49 => 28,  43 => 26,);
    }

    public function getSource()
    {
        return "{#
/**
 * @file
 * Default theme implementation for a meter.
 *
 * Available variables:
 * - display_value: The textual representation of the meter bar.
 * - form: One or more forms to which the <meter> element belongs; multiple
 *   forms separated by spaces.
 * - high: A number specifying the range that is considered to be a high value.
 * - low: A number specifying the range that is considered to be a low value.
 * - max: A number specifying the maximum value of the range.
 * - min: A number specifying the minimum value of the range.
 * - optimum: A number specifying what value is the optimal value for the gauge.
 * - value: A number specifying the current value of the gauge.
 * - percentage: A number specifying the current percentage of the gauge.
 * - attributes: HTML attributes for the containing element.
 * - choice: The choice of a poll.
 *
 * @see template_preprocess()
 * @see template_preprocess_region()
 *
 * @ingroup themeable
 */
#}
<dt class=\"choice-title\">{{ choice }}</dt>
<dd class=\"choice-result\">
  <div{{ attributes }}>
    <div style=\"width: {{ percentage }}%\" class=\"foreground\"></div>
  </div>

  {% if display_value %}
    <div class=\"percent\">{{ display_value }}</div>
  {% endif %}
</dd>
";
    }
}
