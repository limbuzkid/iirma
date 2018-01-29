<?php

/* themes/basic/templates/page--front.html.twig */
class __TwigTemplate_2ca5eaad5ccde73052373e93ca370f99ef503d42f25b58153a110b9fa2df6e36 extends Twig_Template
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
        $tags = array("if" => 14);
        $filters = array("render" => 14);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if'),
                array('render'),
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

        // line 1
        echo "
";
        // line 2
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "html", null, true));
        echo "
<section class=\"banner\" data-scroll=\"completed\">
  ";
        // line 4
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "banner", array()), "html", null, true));
        echo "
</section>
";
        // line 6
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : null), "session", array()), "get", array(0 => "usRLogIn"), "method"), "html", null, true));
        echo "
<section class=\"containt\" data-scroll=\"active\">
  <div class=\"arrow\">
    <a href=\"javascript:;\"><img src=\"/themes/basic/images/arrow1.png\" width=\"17\" height=\"20\" alt=\"arrow\"></a>
  </div>
  <h2>Alumni <strong>Network</strong></h2>
  <article class=\"networkdetails\">
    <p>";
        // line 13
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true));
        echo "</p>
    ";
        // line 14
        if ($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_first", array()))) {
            // line 15
            echo "      ";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_first", array()), "html", null, true));
            echo "
    ";
        }
        // line 17
        echo "      
    ";
        // line 18
        if ($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_second", array()))) {
            // line 19
            echo "      ";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_second", array()), "html", null, true));
            echo "
    ";
        }
        // line 21
        echo "  </article>
  <div class=\"container\">
    <section id=\"content\">
      <div id=\"content-header\">
        ";
        // line 25
        if ((isset($context["action_links"]) ? $context["action_links"] : null)) {
            // line 26
            echo "          <ul class=\"action-links\">";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["action_links"]) ? $context["action_links"] : null), "html", null, true));
            echo "</ul>
        ";
        }
        // line 28
        echo "      </div><!-- /#content-header -->

    </section><!-- /#content -->
  </div>
</section>
<section class=\"updates\" data-scroll=\"\">
  <article class=\"alumDetails\">
    <h2>Alumni <strong>Updates</strong></h2>
    <article class=\"news\">
      <h3>Alumni <strong>News</strong></h3>
      ";
        // line 38
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content_middle_one", array()), "html", null, true));
        echo "
      <a class=\"button\" href=\"/alumni-network/alumni-news\">View All</a>
    </article>
    <article class=\"event\">
      <h3>Upcoming <strong>Events</strong></h3>
      ";
        // line 43
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content_middle_two", array()), "html", null, true));
        echo "
      <a class=\"button\" href=\"/alumni-network/events/upcoming-events\">View All</a>
    </article>  
  </article>
</section>
<section class=\"reconnect\" data-scroll=\"\">
  <article class=\"reconnect-details\">
    ";
        // line 50
        if ($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content_bottom", array()))) {
            // line 51
            echo "      ";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content_bottom", array()), "html", null, true));
            echo "
    ";
        }
        // line 53
        echo "  </article>
</section>

";
        // line 56
        if ($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer", array()))) {
            // line 57
            echo "  <footer data-scroll=\"\"><div class=\"container footer-container\">";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer", array()), "html", null, true));
            echo "</div></footer>
";
        }
        // line 59
        echo "      

";
    }

    public function getTemplateName()
    {
        return "themes/basic/templates/page--front.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  154 => 59,  148 => 57,  146 => 56,  141 => 53,  135 => 51,  133 => 50,  123 => 43,  115 => 38,  103 => 28,  97 => 26,  95 => 25,  89 => 21,  83 => 19,  81 => 18,  78 => 17,  72 => 15,  70 => 14,  66 => 13,  56 => 6,  51 => 4,  46 => 2,  43 => 1,);
    }

    public function getSource()
    {
        return "
{{ page.header }}
<section class=\"banner\" data-scroll=\"completed\">
  {{ page.banner }}
</section>
{{ app.session.get('usRLogIn') }}
<section class=\"containt\" data-scroll=\"active\">
  <div class=\"arrow\">
    <a href=\"javascript:;\"><img src=\"/themes/basic/images/arrow1.png\" width=\"17\" height=\"20\" alt=\"arrow\"></a>
  </div>
  <h2>Alumni <strong>Network</strong></h2>
  <article class=\"networkdetails\">
    <p>{{title}}</p>
    {% if page.sidebar_first|render %}
      {{ page.sidebar_first }}
    {% endif %}
      
    {% if page.sidebar_second|render %}
      {{ page.sidebar_second }}
    {% endif %}
  </article>
  <div class=\"container\">
    <section id=\"content\">
      <div id=\"content-header\">
        {% if action_links %}
          <ul class=\"action-links\">{{ action_links }}</ul>
        {% endif %}
      </div><!-- /#content-header -->

    </section><!-- /#content -->
  </div>
</section>
<section class=\"updates\" data-scroll=\"\">
  <article class=\"alumDetails\">
    <h2>Alumni <strong>Updates</strong></h2>
    <article class=\"news\">
      <h3>Alumni <strong>News</strong></h3>
      {{ page.content_middle_one}}
      <a class=\"button\" href=\"/alumni-network/alumni-news\">View All</a>
    </article>
    <article class=\"event\">
      <h3>Upcoming <strong>Events</strong></h3>
      {{page.content_middle_two}}
      <a class=\"button\" href=\"/alumni-network/events/upcoming-events\">View All</a>
    </article>  
  </article>
</section>
<section class=\"reconnect\" data-scroll=\"\">
  <article class=\"reconnect-details\">
    {% if page.content_bottom|render %}
      {{ page.content_bottom }}
    {% endif %}
  </article>
</section>

{% if page.footer|render %}
  <footer data-scroll=\"\"><div class=\"container footer-container\">{{ page.footer }}</div></footer>
{% endif %}
      

";
    }
}
