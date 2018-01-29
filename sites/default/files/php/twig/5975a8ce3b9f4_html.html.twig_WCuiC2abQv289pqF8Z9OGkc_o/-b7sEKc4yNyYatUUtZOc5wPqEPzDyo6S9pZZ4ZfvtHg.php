<?php

/* themes/basic/templates/html.html.twig */
class __TwigTemplate_7ff4584418f827c5ab2c6caccc8fc94168f7459360b546141dd3331425074ef0 extends Twig_Template
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
        $tags = array("if" => 34, "set" => 58, "for" => 59);
        $filters = array("safe_join" => 48, "merge" => 60, "clean_class" => 60, "render" => 63);
        $functions = array("attach_library" => 35);

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if', 'set', 'for'),
                array('safe_join', 'merge', 'clean_class', 'render'),
                array('attach_library')
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

        // line 33
        echo "<!DOCTYPE html>
";
        // line 34
        if ($this->getAttribute((isset($context["ie_enabled_versions"]) ? $context["ie_enabled_versions"] : null), "ie8", array())) {
            // line 35
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->env->getExtension('drupal_core')->attachLibrary("basic/ie8"), "html", null, true));
            echo "
";
        }
        // line 37
        if (($this->getAttribute((isset($context["ie_enabled_versions"]) ? $context["ie_enabled_versions"] : null), "ie9", array()) || $this->getAttribute((isset($context["ie_enabled_versions"]) ? $context["ie_enabled_versions"] : null), "ie8", array()))) {
            // line 38
            echo "  <!--[if lt IE 7]>     <html";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["html_attributes"]) ? $context["html_attributes"] : null), "addClass", array(0 => "no-js", 1 => "lt-ie9", 2 => "lt-ie8", 3 => "lt-ie7"), "method"), "html", null, true));
            echo "><![endif]-->
  <!--[if IE 7]>        <html";
            // line 39
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["html_attributes"]) ? $context["html_attributes"] : null), "removeClass", array(0 => "lt-ie7"), "method"), "html", null, true));
            echo "><![endif]-->
  <!--[if IE 8]>        <html";
            // line 40
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["html_attributes"]) ? $context["html_attributes"] : null), "removeClass", array(0 => "lt-ie8"), "method"), "html", null, true));
            echo "><![endif]-->
  <!--[if gt IE 8]><!-->
<html";
            // line 42
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["html_attributes"]) ? $context["html_attributes"] : null), "removeClass", array(0 => "lt-ie9"), "method"), "html", null, true));
            echo " id=\"beforeLoad\"><!--<![endif]-->
";
        } else {
            // line 44
            echo "<html id=\"beforeLoad\">
";
        }
        // line 46
        echo "  <head>
    <head-placeholder token=\"";
        // line 47
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["placeholder_token"]) ? $context["placeholder_token"] : null), "html", null, true));
        echo "\">
    <!--<title>";
        // line 48
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->env->getExtension('drupal_core')->safeJoin($this->env, (isset($context["head_title"]) ? $context["head_title"] : null), " | ")));
        echo "</title>-->
    <title>";
        // line 49
        if ( !(isset($context["root_path"]) ? $context["root_path"] : null)) {
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar("Welcome to IAA"));
        } else {
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->env->getExtension('drupal_core')->safeJoin($this->env, (isset($context["head_title"]) ? $context["head_title"] : null), " | ")));
        }
        echo "</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0\">
    <css-placeholder token=\"";
        // line 52
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["placeholder_token"]) ? $context["placeholder_token"] : null), "html", null, true));
        echo "\">
    <js-placeholder token=\"";
        // line 53
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["placeholder_token"]) ? $context["placeholder_token"] : null), "html", null, true));
        echo "\">
    <script src=\"https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js\"></script>
    <script src=\"https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js\"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  ";
        // line 58
        $context["classes"] = array();
        // line 59
        echo "  ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["user"]) ? $context["user"] : null), "roles", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["role"]) {
            // line 60
            echo "    ";
            $context["classes"] = twig_array_merge((isset($context["classes"]) ? $context["classes"] : null), array(0 => ("role--" . \Drupal\Component\Utility\Html::getClass($context["role"]))));
            // line 61
            echo "  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['role'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 62
        echo "
  ";
        // line 63
        $context["sidebar_first"] = $this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_first", array()));
        // line 64
        echo "  ";
        $context["sidebar_second"] = $this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "sidebar_second", array()));
        // line 65
        echo "  <body";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => (isset($context["classes"]) ? $context["classes"] : null), 1 => ((        // line 66
(isset($context["logged_in"]) ? $context["logged_in"] : null)) ? ("logged-in") : ("")), 2 => ((        // line 67
(isset($context["is_front"]) ? $context["is_front"] : null)) ? ("page-front") : ("")), 3 => (( !        // line 68
(isset($context["is_front"]) ? $context["is_front"] : null)) ? ("with-subnav") : ("")), 4 => ((        // line 69
(isset($context["sidebar_first"]) ? $context["sidebar_first"] : null)) ? ("sidebar-first") : ("")), 5 => ((        // line 70
(isset($context["sidebar_second"]) ? $context["sidebar_second"] : null)) ? ("sidebar-second") : ("")), 6 => ((((        // line 71
(isset($context["sidebar_first"]) ? $context["sidebar_first"] : null) &&  !(isset($context["sidebar_second"]) ? $context["sidebar_second"] : null)) || ((isset($context["sidebar_second"]) ? $context["sidebar_second"] : null) &&  !(isset($context["sidebar_first"]) ? $context["sidebar_first"] : null)))) ? ("one-sidebar") : ("")), 7 => (((        // line 72
(isset($context["sidebar_first"]) ? $context["sidebar_first"] : null) && (isset($context["sidebar_second"]) ? $context["sidebar_second"] : null))) ? ("two-sidebars") : ("")), 8 => ((( !        // line 73
(isset($context["sidebar_first"]) ? $context["sidebar_first"] : null) &&  !(isset($context["sidebar_second"]) ? $context["sidebar_second"] : null))) ? ("no-sidebar") : (""))), "method"), "html", null, true));
        // line 74
        echo ">
    ";
        // line 75
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["page_top"]) ? $context["page_top"] : null), "html", null, true));
        echo "
    <div class=\"transfer\"></div>
    <div class=\"autoSearchbox\">
      <form action=\"/search\" method=\"post\" id=\"srchFrm\">
        <input type=\"text\" placeholder=\"Search\" name=\"srchTxt\">
        <input type=\"hidden\" name=\"actFrm\" value=\"out\">
        <a href=\"javascript:;\" class=\"goBtn\">Go</a>
      </form>  
    </div>
    ";
        // line 84
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["page"]) ? $context["page"] : null), "html", null, true));
        echo "
    ";
        // line 85
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["page_bottom"]) ? $context["page_bottom"] : null), "html", null, true));
        echo "
    <js-bottom-placeholder token=\"";
        // line 86
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["placeholder_token"]) ? $context["placeholder_token"] : null), "html", null, true));
        echo "\">
    ";
        // line 87
        if ($this->getAttribute((isset($context["browser_sync"]) ? $context["browser_sync"] : null), "enabled", array())) {
            // line 88
            echo "      <script id=\"__bs_script__\">
      document.write(\"<script async src='http://";
            // line 89
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["browser_sync"]) ? $context["browser_sync"] : null), "host", array()), "html", null, true));
            echo ":";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["browser_sync"]) ? $context["browser_sync"] : null), "port", array()), "html", null, true));
            echo "/browser-sync/browser-sync-client.js'><\\/script>\".replace(\"HOST\", location.hostname));
      </script>
    ";
        }
        // line 92
        echo "  </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "themes/basic/templates/html.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  186 => 92,  178 => 89,  175 => 88,  173 => 87,  169 => 86,  165 => 85,  161 => 84,  149 => 75,  146 => 74,  144 => 73,  143 => 72,  142 => 71,  141 => 70,  140 => 69,  139 => 68,  138 => 67,  137 => 66,  135 => 65,  132 => 64,  130 => 63,  127 => 62,  121 => 61,  118 => 60,  113 => 59,  111 => 58,  103 => 53,  99 => 52,  89 => 49,  85 => 48,  81 => 47,  78 => 46,  74 => 44,  69 => 42,  64 => 40,  60 => 39,  55 => 38,  53 => 37,  48 => 35,  46 => 34,  43 => 33,);
    }

    public function getSource()
    {
        return "{#
/**
 * @file
 * Default theme implementation for the basic structure of a single Drupal page.
 *
 * Variables:
 * - logged_in: A flag indicating if user is logged in.
 * - root_path: The root path of the current page (e.g., node, admin, user).
 * - node_type: The content type for the current node, if the page is a node.
 * - css: A list of CSS files for the current page.
 * - head: Markup for the HEAD element (including meta tags, keyword tags, and
 *   so on).
 * - head_title: A modified version of the page title, for use in the TITLE tag.
 * - head_title_array: List of text elements that make up the head_title
 *   variable. May contain or more of the following:
 *   - title: The title of the page.
 *   - name: The name of the site.
 *   - slogan: The slogan of the site.
 * - page_top: Initial rendered markup. This should be printed before 'page'.
 * - page: The rendered page markup.
 * - page_bottom: Closing rendered markup. This variable should be printed after
 *   'page'.
 * - styles: Style tags necessary to import all necessary CSS files in the head.
 * - scripts: Script tags necessary to load the JavaScript files and settings
 *   in the head.
 * - db_offline: A flag indicating if the database is offline.
 *
 * @see template_preprocess_html()
 *
 * @ingroup themeable
 */
#}
<!DOCTYPE html>
{% if ie_enabled_versions.ie8 %}
  {{- attach_library('basic/ie8') }}
{% endif %}
{% if ie_enabled_versions.ie9 or ie_enabled_versions.ie8 %}
  <!--[if lt IE 7]>     <html{{ html_attributes.addClass('no-js', 'lt-ie9', 'lt-ie8', 'lt-ie7') }}><![endif]-->
  <!--[if IE 7]>        <html{{ html_attributes.removeClass('lt-ie7') }}><![endif]-->
  <!--[if IE 8]>        <html{{ html_attributes.removeClass('lt-ie8') }}><![endif]-->
  <!--[if gt IE 8]><!-->
<html{{ html_attributes.removeClass('lt-ie9') }} id=\"beforeLoad\"><!--<![endif]-->
{% else -%}
  <html id=\"beforeLoad\">
{% endif %}
  <head>
    <head-placeholder token=\"{{ placeholder_token }}\">
    <!--<title>{{ head_title|safe_join(' | ') }}</title>-->
    <title>{% if not root_path %}{{ 'Welcome to IAA' }}{% else %}{{ head_title|safe_join(' | ') }}{% endif %}</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0\">
    <css-placeholder token=\"{{ placeholder_token }}\">
    <js-placeholder token=\"{{ placeholder_token }}\">
    <script src=\"https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js\"></script>
    <script src=\"https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js\"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  {% set classes = [] %}
  {% for role in user.roles %}
    {% set classes = classes|merge(['role--' ~ role|clean_class]) %}
  {% endfor %}

  {% set sidebar_first = page.sidebar_first|render %}
  {% set sidebar_second = page.sidebar_second|render %}
  <body{{ attributes.addClass(classes,
    logged_in ? 'logged-in',
    is_front ? 'page-front',
    not is_front ? 'with-subnav',
    sidebar_first ? 'sidebar-first',
    sidebar_second ? 'sidebar-second',
    (sidebar_first and not sidebar_second) or (sidebar_second and not sidebar_first) ? 'one-sidebar',
    (sidebar_first and sidebar_second) ? 'two-sidebars',
    (not sidebar_first and not sidebar_second) ? 'no-sidebar'
  ) }}>
    {{ page_top }}
    <div class=\"transfer\"></div>
    <div class=\"autoSearchbox\">
      <form action=\"/search\" method=\"post\" id=\"srchFrm\">
        <input type=\"text\" placeholder=\"Search\" name=\"srchTxt\">
        <input type=\"hidden\" name=\"actFrm\" value=\"out\">
        <a href=\"javascript:;\" class=\"goBtn\">Go</a>
      </form>  
    </div>
    {{ page }}
    {{ page_bottom }}
    <js-bottom-placeholder token=\"{{ placeholder_token }}\">
    {% if browser_sync.enabled %}
      <script id=\"__bs_script__\">
      document.write(\"<script async src='http://{{ browser_sync.host }}:{{ browser_sync.port }}/browser-sync/browser-sync-client.js'><\\/script>\".replace(\"HOST\", location.hostname));
      </script>
    {% endif %}
  </body>
</html>
";
    }
}
