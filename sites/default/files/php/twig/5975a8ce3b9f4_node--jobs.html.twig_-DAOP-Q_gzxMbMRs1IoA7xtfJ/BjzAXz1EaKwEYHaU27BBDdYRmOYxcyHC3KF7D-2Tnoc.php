<?php

/* themes/basic/templates/node--jobs.html.twig */
class __TwigTemplate_bd465dd800fcc943202b3db4f7a6aa244e0e1532475b18656d80eb11402e18f7 extends Twig_Template
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
        $tags = array("set" => 2, "if" => 40);
        $filters = array("raw" => 20);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('set', 'if'),
                array('raw'),
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
        echo "
<h3 class=\"";
        // line 17
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["nodetype"]) ? $context["nodetype"] : null), "html", null, true));
        echo " ";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["currentnodeid"]) ? $context["currentnodeid"] : null), "html", null, true));
        echo " ";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["currentuserid"]) ? $context["currentuserid"] : null), "html", null, true));
        echo "\">";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($this->getAttribute((isset($context["node"]) ? $context["node"] : null), "title", array()), "value", array()), "html", null, true));
        echo "</h3>
<span class=\"areaLoc\">";
        // line 18
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["node"]) ? $context["node"] : null), "field_company", array()), "entity", array()), "name", array()), "value", array()), "html", null, true));
        echo "</span>
<span class=\"minExp\">Min Exp: ";
        // line 19
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($this->getAttribute((isset($context["node"]) ? $context["node"] : null), "field_experience", array()), "value", array()), "html", null, true));
        echo " - ";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($this->getAttribute((isset($context["node"]) ? $context["node"] : null), "field_maximum_experience", array()), "value", array()), "html", null, true));
        echo " Yrs.</span>
";
        // line 20
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute($this->getAttribute((isset($context["node"]) ? $context["node"] : null), "body", array()), "value", array())));
        echo "
<div class=\"btnsec\">
    <a class=\"button applyirmaprofile\" href=\"javascript:;\" data-nodeid = \"";
        // line 22
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($this->getAttribute((isset($context["node"]) ? $context["node"] : null), "nid", array()), "value", array()), "html", null, true));
        echo "\">Apply with IRMA Profile</a>
    <a class=\"buttonBack\" href=\"/alumni-network/careers/job-listing\">Back</a>
</div>
<div class=\"uploadresume\">
    <p>Don’t wish to apply your IRMA profile? No worries! You can upload your resume and Apply.</p>
    <div class=\"uploadBtn\">
        <form method=\"POST\" id=\"uploadresume\" enctype=\"multipart/form-data\">
            <a class=\"uploadBtn\" href=\"javascript:;\">Select File</a>
            <input type=\"file\" name=\"file\"/>
            <br/><br/>
            <input type=\"hidden\" id=\"nodeid\" name=\"nodeid\" value=\"";
        // line 32
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($this->getAttribute((isset($context["node"]) ? $context["node"] : null), "nid", array()), "value", array()), "html", null, true));
        echo "\" />
          <!--<input type=\"submit\" name=\"submit\" value=\"Upload Resume\" />-->
        </form>
    </div>
    <a class=\"uploadBtn uploadresumeclick fileUpload\" href=\"javascript:;\" data-nodeid = \"";
        // line 36
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($this->getAttribute((isset($context["node"]) ? $context["node"] : null), "nid", array()), "value", array()), "html", null, true));
        echo "\">Upload Resume</a>
</div>
<p>Have any questions in mind before you apply? <a class=\"buttonBack\" href=\"/alumni-network/contact-irma\" target=\"_blank\"> Contact Us</a>.</p>

  ";
        // line 40
        if ($this->getAttribute((isset($context["content"]) ? $context["content"] : null), "links", array())) {
            // line 41
            echo "    <div class=\"links\">
      ";
            // line 42
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["content"]) ? $context["content"] : null), "links", array()), "html", null, true));
            echo "
    </div><!-- /.links -->
  ";
        }
        // line 45
        echo "
";
    }

    public function getTemplateName()
    {
        return "themes/basic/templates/node--jobs.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  114 => 45,  108 => 42,  105 => 41,  103 => 40,  96 => 36,  89 => 32,  76 => 22,  71 => 20,  65 => 19,  61 => 18,  51 => 17,  48 => 16,  45 => 5,  43 => 2,);
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

<h3 class=\"{{ nodetype }} {{ currentnodeid }} {{ currentuserid }}\">{{ node.title.value }}</h3>
<span class=\"areaLoc\">{{ node.field_company.entity.name.value }}</span>
<span class=\"minExp\">Min Exp: {{ node.field_experience.value }} - {{ node.field_maximum_experience.value }} Yrs.</span>
{{ node.body.value|raw }}
<div class=\"btnsec\">
    <a class=\"button applyirmaprofile\" href=\"javascript:;\" data-nodeid = \"{{ node.nid.value }}\">Apply with IRMA Profile</a>
    <a class=\"buttonBack\" href=\"/alumni-network/careers/job-listing\">Back</a>
</div>
<div class=\"uploadresume\">
    <p>Don’t wish to apply your IRMA profile? No worries! You can upload your resume and Apply.</p>
    <div class=\"uploadBtn\">
        <form method=\"POST\" id=\"uploadresume\" enctype=\"multipart/form-data\">
            <a class=\"uploadBtn\" href=\"javascript:;\">Select File</a>
            <input type=\"file\" name=\"file\"/>
            <br/><br/>
            <input type=\"hidden\" id=\"nodeid\" name=\"nodeid\" value=\"{{ node.nid.value }}\" />
          <!--<input type=\"submit\" name=\"submit\" value=\"Upload Resume\" />-->
        </form>
    </div>
    <a class=\"uploadBtn uploadresumeclick fileUpload\" href=\"javascript:;\" data-nodeid = \"{{ node.nid.value }}\">Upload Resume</a>
</div>
<p>Have any questions in mind before you apply? <a class=\"buttonBack\" href=\"/alumni-network/contact-irma\" target=\"_blank\"> Contact Us</a>.</p>

  {% if content.links %}
    <div class=\"links\">
      {{ content.links }}
    </div><!-- /.links -->
  {% endif %}

";
    }
}
