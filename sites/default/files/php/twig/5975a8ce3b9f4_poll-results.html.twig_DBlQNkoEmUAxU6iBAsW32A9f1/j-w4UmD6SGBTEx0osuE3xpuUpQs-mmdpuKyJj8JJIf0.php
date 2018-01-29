<?php

/* modules/contrib/poll/templates/poll-results.html.twig */
class __TwigTemplate_939789c87610b1242b9c3eaf0d41690e8da05ed94c452c61076cf1978242ab90 extends Twig_Template
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
        $tags = array("if" => 22);
        $filters = array("t" => 31);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if'),
                array('t'),
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

        // line 21
        echo "<div class=\"poll\">
  ";
        // line 22
        if ((isset($context["show_question"]) ? $context["show_question"] : null)) {
            // line 23
            echo "    <h3 class=\"poll-question\">";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["question"]) ? $context["question"] : null), "html", null, true));
            echo "</h3>
  ";
        } else {
            // line 25
            echo "    <h3 class=\"poll-results-title\">";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["results_title"]) ? $context["results_title"] : null), "html", null, true));
            echo "</h3>
  ";
        }
        // line 27
        echo "  <dl>
    ";
        // line 28
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["results"]) ? $context["results"] : null), "html", null, true));
        echo "
  </dl>
  <div class=\"total\">
    ";
        // line 31
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Total votes: ")));
        echo " ";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["votes"]) ? $context["votes"] : null), "html", null, true));
        echo "
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "modules/contrib/poll/templates/poll-results.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  69 => 31,  63 => 28,  60 => 27,  54 => 25,  48 => 23,  46 => 22,  43 => 21,);
    }

    public function getSource()
    {
        return "{#
/**
 * @file
 * Default theme implementation to display the poll results in a block.
 *
 * Variables available:
 * - title: The title of the poll.
 * - results: The results of the poll.
 * - votes: The total results in the poll.
 * - links: Links in the poll.
 * - pid: The pid of the poll
 * - cancel_form: A form to cancel the user's vote, if allowed.
 * - raw_links: The raw array of links.
 * - vote: The choice number of the current user's vote.
 *
 * @see template_preprocess_poll_results()
 *
 * @ingroup themeable
 */
#}
<div class=\"poll\">
  {% if show_question %}
    <h3 class=\"poll-question\">{{ question }}</h3>
  {% else %}
    <h3 class=\"poll-results-title\">{{ results_title }}</h3>
  {% endif %}
  <dl>
    {{ results }}
  </dl>
  <div class=\"total\">
    {{ 'Total votes: '|t }} {{ votes }}
  </div>
</div>
";
    }
}
