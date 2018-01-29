<?php

/* themes/basic/templates/page--irma--avail-campus-facilities.html.twig */
class __TwigTemplate_33150029d8dd880e1c053f2cd6bf6462bbc6bb9fbcbf25b5e8a51c00c2389d9c extends Twig_Template
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
        $tags = array("if" => 8, "for" => 11);
        $filters = array("raw" => 17, "format" => 129, "render" => 1103);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if', 'for'),
                array('raw', 'format', 'render'),
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
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "header", array()), "html", null, true));
        echo "
<section class=\"innerbanner\">";
        // line 2
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "banner", array()), "html", null, true));
        echo "</section>
<section class=\"mainWrapper myAccountP formPage\" style=\"float:none\">  
  <div class=\"container\">
    ";
        // line 5
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "help", array()), "html", null, true));
        echo "
    ";
        // line 6
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "content", array()), "html", null, true));
        echo "
    <div id=\"content-area\" class=\"detailContent\">
      ";
        // line 8
        if (((isset($context["cur_page"]) ? $context["cur_page"] : null) == "landing_page")) {
            // line 9
            echo "        <div class=\"managementLest newsletter_list\">
          <ul>
            ";
            // line 11
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["data_obj"]) ? $context["data_obj"] : null));
            foreach ($context['_seq'] as $context["key"] => $context["value"]) {
                // line 12
                echo "              <li>
                <div class=\"imgSec\"><img src=\"";
                // line 13
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["value"], "image", array()), "html", null, true));
                echo "\" alt=\"\"></div>
                <div class=\"contentSec\">
                  <div class=\"titleSec\">
                    <h4>";
                // line 16
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["value"], "title", array()), "html", null, true));
                echo "</h4>
                    ";
                // line 17
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute($context["value"], "desc", array())));
                echo "
                  </div>
                </div>
                <div class=\"btnSec arrowBtn\"><a class=\"button\" href=\"";
                // line 20
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["value"], "alias", array()), "html", null, true));
                echo "\">Read more</a></div>
              </li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 23
            echo "          </ul>
        </div>
      ";
        }
        // line 26
        echo "      ";
        if ((isset($context["logged_in"]) ? $context["logged_in"] : null)) {
            // line 27
            echo "        ";
            if (((isset($context["cur_page"]) ? $context["cur_page"] : null) == "etdc")) {
                // line 28
                echo "          <div class=\"innerContainer\">
            <h3>ETDC</h3>
          <div class=\"accountInfoSec\">
            <div class=\"formFealds\" rel=\"";
                // line 31
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "user_type", array())));
                echo "\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"";
                // line 35
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "first_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"";
                // line 39
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "last_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        ";
                // line 48
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "batch_no", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        ";
                // line 60
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "organisation", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" onblur=\"clearText(this)\" onfocus=\"clearText(this)\" value=\"";
                // line 67
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "designation", array())));
                echo "\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"";
                // line 71
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mail", array())));
                echo "\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        ";
                // line 80
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        ";
                // line 92
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "state", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        ";
                // line 104
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "city", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"";
                // line 113
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country_code", array())));
                echo "\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"";
                // line 114
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mobile", array())));
                echo "\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Select</option>
                          ";
                // line 128
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 129
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 131
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Select</option>
                          ";
                // line 142
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 143
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 145
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Select</option>
                          ";
                // line 160
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 161
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 163
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Select</option>
                          ";
                // line 174
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 175
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 177
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" maxlength=\"100\">
                </li>
                <li>
                    <label>No of Persons <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\">No of Persons *</a>
                      <select class=\"numPers\">
                        <option value=\"\">Select</option>
                          ";
                // line 199
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 10));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 200
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 202
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>No of rooms required <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"numRooms\">
                        <option value=\"\">Select</option>
                          ";
                // line 213
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 10));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 214
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 216
                echo "                      </select>
                    </div>
                  </div>
                </li>
                
                <li>
                    <label>Food To Be Included <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox incFood\">
                    <div class=\"box\"><input type=\"radio\" name=\"incFood\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"incFood\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                <li>
                    <label>Food Preference <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox prefFood\">
                    <div class=\"box\"><input type=\"radio\" name=\"prefFood\" id=\"box5\" value=\"veg\" checked><label for=\"box5\"><span></span>Vegetarian</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"prefFood\" id=\"box6\" value=\"non-veg\"><label for=\"box6\"><span></span>Non-Vegetarian</label></div>
                  </div>
                </li>
                               
                <a class=\"button btnEtdc\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"";
                // line 238
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "token", array())));
                echo "\">
            </div>
          </div>
        </div>
      ";
            }
            // line 243
            echo "      
      ";
            // line 244
            if (((isset($context["cur_page"]) ? $context["cur_page"] : null) == "wifi-access")) {
                // line 245
                echo "        <div class=\"innerContainer\">
            <h3>Wi-fi Access</h3>
          <div class=\"accountInfoSec\">
            <div class=\"formFealds\" rel=\"";
                // line 248
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "user_type", array())));
                echo "\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"";
                // line 252
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "first_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"";
                // line 256
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "last_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        ";
                // line 265
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "batch_no", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        ";
                // line 277
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "organisation", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" value=\"";
                // line 284
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "designation", array())));
                echo "\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"";
                // line 288
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mail", array())));
                echo "\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        ";
                // line 297
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        ";
                // line 309
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "state", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        ";
                // line 321
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "city", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"";
                // line 330
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country_code", array())));
                echo "\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"";
                // line 331
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mobile", array())));
                echo "\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Hour</option>
                          ";
                // line 345
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 346
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 348
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Minute</option>
                          ";
                // line 359
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 360
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 362
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Hour</option>
                          ";
                // line 377
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 378
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 380
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Minute</option>
                          ";
                // line 391
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 392
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 394
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" value=\"State Purpose *\" maxlength=\"100\">
                </li>
                <li>
                    <label>Need Access To Wi-fi <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox accWifi\">
                    <div class=\"box\"><input type=\"radio\" name=\"accWifi\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"accWifi\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                               
                <a class=\"button btnWifi\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"";
                // line 419
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "token", array())));
                echo "\">
            </div>
          </div>
        </div>
      ";
            }
            // line 423
            echo "      
      ";
            // line 424
            if (((isset($context["cur_page"]) ? $context["cur_page"] : null) == "sac")) {
                // line 425
                echo "        <div class=\"innerContainer\">
            <h3>Students' Activity Centre</h3>
          <div class=\"accountInfoSec\">
            <div class=\"formFealds\" rel=\"";
                // line 428
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "user_type", array())));
                echo "\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"";
                // line 432
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "first_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"";
                // line 436
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "last_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        ";
                // line 445
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "batch_no", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        ";
                // line 457
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "organisation", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" value=\"";
                // line 464
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "designation", array())));
                echo "\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"";
                // line 468
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mail", array())));
                echo "\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        ";
                // line 477
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        ";
                // line 489
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "state", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        ";
                // line 501
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "city", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"";
                // line 510
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country_code", array())));
                echo "\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"";
                // line 511
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mobile", array())));
                echo "\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Hour</option>
                          ";
                // line 525
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 526
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 528
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Minute</option>
                          ";
                // line 539
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 540
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 542
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Hour</option>
                          ";
                // line 557
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 558
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 560
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Minute</option>
                          ";
                // line 571
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 572
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 574
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" maxlength=\"100\">
                </li>
                <li>
                    <label>Need Access To SAC <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox accSac\">
                    <div class=\"box\"><input type=\"radio\" name=\"accSac\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"accSac\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                               
                <a class=\"button btnSac\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"";
                // line 599
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "token", array())));
                echo "\">
            </div>
          </div>
        </div>
      ";
            }
            // line 604
            echo "      
      ";
            // line 605
            if (((isset($context["cur_page"]) ? $context["cur_page"] : null) == "students-mess")) {
                // line 606
                echo "        <div class=\"innerContainer\">
            <h3>Students' Mess</h3>
          <div class=\"accountInfoSec\">
            <div class=\"formFealds\" rel=\"";
                // line 609
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "user_type", array())));
                echo "\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"";
                // line 613
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "first_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"";
                // line 617
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "last_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        ";
                // line 626
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "batch_no", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        ";
                // line 638
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "organisation", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" onblur=\"clearText(this)\" onfocus=\"clearText(this)\" value=\"";
                // line 645
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "designation", array())));
                echo "\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"";
                // line 649
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mail", array())));
                echo "\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        ";
                // line 658
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        ";
                // line 670
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "state", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        ";
                // line 682
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "city", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"";
                // line 691
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country_code", array())));
                echo "\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"";
                // line 692
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mobile", array())));
                echo "\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Select</option>
                          ";
                // line 706
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 707
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 709
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Select</option>
                          ";
                // line 720
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 721
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 723
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Select</option>
                          ";
                // line 738
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 739
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 741
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Select</option>
                          ";
                // line 752
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 753
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 755
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" maxlength=\"100\">
                </li>
                <li>
                    <label>Arrange Food At Students' Mess <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox arrFood\">
                    <div class=\"box\"><input type=\"radio\" name=\"arrFood\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"arrFood\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                <li>
                    <label>No of Persons <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"noPers\">
                        <option value=\"\">Select</option>
                          ";
                // line 784
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 10));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 785
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\">";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 787
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <a class=\"button btnMess\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"";
                // line 793
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "token", array())));
                echo "\">
            </div>
          </div>
        </div>
      ";
            }
            // line 798
            echo "      
      ";
            // line 799
            if (((isset($context["cur_page"]) ? $context["cur_page"] : null) == "library")) {
                // line 800
                echo "        <div class=\"innerContainer\">
          <div class=\"accountInfoSec\">
              <h3>Library</h3>
            <div class=\"formFealds\" rel=\"";
                // line 803
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "user_type", array())));
                echo "\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"";
                // line 807
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "first_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"";
                // line 811
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "last_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        ";
                // line 820
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "batch_no", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        ";
                // line 832
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "organisation", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" onblur=\"clearText(this)\" onfocus=\"clearText(this)\" value=\"";
                // line 839
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "designation", array())));
                echo "\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"";
                // line 843
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mail", array())));
                echo "\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        ";
                // line 852
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        ";
                // line 864
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "state", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        ";
                // line 876
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "city", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"";
                // line 885
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country_code", array())));
                echo "\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"";
                // line 886
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mobile", array())));
                echo "\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Select</option>
                          ";
                // line 900
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 901
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 903
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Select</option>
                          ";
                // line 914
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 915
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 917
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Select</option>
                          ";
                // line 932
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 23));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 933
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 935
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Select</option>
                          ";
                // line 946
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(0, 59));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 947
                    echo "                            <option value=\"";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $context["i"], "html", null, true));
                    echo "\"> ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, sprintf("%02d", $context["i"]), "html", null, true));
                    echo "</option>
                          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 949
                echo "                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" maxlength=\"100\">
                </li>
                <li>
                    <label>Need Access To Library <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox accLib\">
                    <div class=\"box\"><input type=\"radio\" name=\"accLib\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"accLib\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                <a class=\"button btnLibr\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"";
                // line 973
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "token", array())));
                echo "\">
            </div>
          </div>
        </div>
      ";
            }
            // line 978
            echo "      ";
            if (((isset($context["cur_page"]) ? $context["cur_page"] : null) == "MDP")) {
                // line 979
                echo "        <div class=\"innerContainer\">
          <div class=\"accountInfoSec\">
            <h3>Management Development Programme</h3>
            <div class=\"formFealds\" rel=\"";
                // line 982
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "user_type", array())));
                echo "\">
              <ul>
                <li>
                    <label>First Name <span class=\"required\">*</span></label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"";
                // line 986
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "first_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Last Name <span class=\"required\">*</span></label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"";
                // line 990
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "last_name", array()), "html", null, true));
                echo "\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink ";
                // line 996
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "batch_no", array())));
                echo "\" href=\"javascript:;\">";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "batch_no", array()), "html", null, true));
                echo "</a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        ";
                // line 999
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "batch_no", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        ";
                // line 1011
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "organisation", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" value=\"";
                // line 1018
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "designation", array())));
                echo "\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"";
                // line 1022
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mail", array())));
                echo "\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        ";
                // line 1031
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        ";
                // line 1043
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "state", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        ";
                // line 1055
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "city", array())));
                echo "
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"";
                // line 1063
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "country_code", array())));
                echo "\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"";
                // line 1064
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "mobile", array())));
                echo "\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>MDP Name <span class=\"required\">*</span></label>
                    <input class=\"mdpName\" type=\"text\" maxlength=\"100\">
                </li>
                <li>
                    <label>MDP Start Date <span class=\"required\">*</span></label>
                    <input class=\"startDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>MDP End Date <span class=\"required\">*</span></label>
                    <input class=\"enDate\" type=\"text\" value=\"\" maxlength=\"10\">
                </li>
                <li>
                    <label>MDP Query <span class=\"required\">*</span></label>
                    <textarea class=\"mdpQry\" type=\"text\" maxlength=\"100\"></textarea>
                </li>
        
                <a class=\"button btnMdp\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"";
                // line 1086
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["data_obj"]) ? $context["data_obj"] : null), "token", array())));
                echo "\">
            </div>
          </div>
        </div>
        ";
            }
            // line 1091
            echo "      ";
        } else {
            // line 1092
            echo "        <div class=\"detailContent trigger\">
          <div class=\"managementLest\">
            <h2>You need to log in to view this page</h2>
          </div>
        </div>
      ";
        }
        // line 1098
        echo "    </div>
  </div>

</section>

";
        // line 1103
        if ($this->env->getExtension('drupal_core')->renderVar($this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer", array()))) {
            // line 1104
            echo "  <footer><div class=\"container footer-container\">";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["page"]) ? $context["page"] : null), "footer", array()), "html", null, true));
            echo "</div></footer>
";
        }
        // line 1106
        echo "

";
    }

    public function getTemplateName()
    {
        return "themes/basic/templates/page--irma--avail-campus-facilities.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1788 => 1106,  1782 => 1104,  1780 => 1103,  1773 => 1098,  1765 => 1092,  1762 => 1091,  1754 => 1086,  1729 => 1064,  1725 => 1063,  1714 => 1055,  1699 => 1043,  1684 => 1031,  1672 => 1022,  1665 => 1018,  1655 => 1011,  1640 => 999,  1632 => 996,  1623 => 990,  1616 => 986,  1609 => 982,  1604 => 979,  1601 => 978,  1593 => 973,  1567 => 949,  1556 => 947,  1552 => 946,  1539 => 935,  1528 => 933,  1524 => 932,  1507 => 917,  1496 => 915,  1492 => 914,  1479 => 903,  1468 => 901,  1464 => 900,  1447 => 886,  1443 => 885,  1431 => 876,  1416 => 864,  1401 => 852,  1389 => 843,  1382 => 839,  1372 => 832,  1357 => 820,  1345 => 811,  1338 => 807,  1331 => 803,  1326 => 800,  1324 => 799,  1321 => 798,  1313 => 793,  1305 => 787,  1294 => 785,  1290 => 784,  1259 => 755,  1248 => 753,  1244 => 752,  1231 => 741,  1220 => 739,  1216 => 738,  1199 => 723,  1188 => 721,  1184 => 720,  1171 => 709,  1160 => 707,  1156 => 706,  1139 => 692,  1135 => 691,  1123 => 682,  1108 => 670,  1093 => 658,  1081 => 649,  1074 => 645,  1064 => 638,  1049 => 626,  1037 => 617,  1030 => 613,  1023 => 609,  1018 => 606,  1016 => 605,  1013 => 604,  1005 => 599,  978 => 574,  967 => 572,  963 => 571,  950 => 560,  939 => 558,  935 => 557,  918 => 542,  907 => 540,  903 => 539,  890 => 528,  879 => 526,  875 => 525,  858 => 511,  854 => 510,  842 => 501,  827 => 489,  812 => 477,  800 => 468,  793 => 464,  783 => 457,  768 => 445,  756 => 436,  749 => 432,  742 => 428,  737 => 425,  735 => 424,  732 => 423,  724 => 419,  697 => 394,  686 => 392,  682 => 391,  669 => 380,  658 => 378,  654 => 377,  637 => 362,  626 => 360,  622 => 359,  609 => 348,  598 => 346,  594 => 345,  577 => 331,  573 => 330,  561 => 321,  546 => 309,  531 => 297,  519 => 288,  512 => 284,  502 => 277,  487 => 265,  475 => 256,  468 => 252,  461 => 248,  456 => 245,  454 => 244,  451 => 243,  443 => 238,  419 => 216,  408 => 214,  404 => 213,  391 => 202,  380 => 200,  376 => 199,  352 => 177,  341 => 175,  337 => 174,  324 => 163,  313 => 161,  309 => 160,  292 => 145,  281 => 143,  277 => 142,  264 => 131,  253 => 129,  249 => 128,  232 => 114,  228 => 113,  216 => 104,  201 => 92,  186 => 80,  174 => 71,  167 => 67,  157 => 60,  142 => 48,  130 => 39,  123 => 35,  116 => 31,  111 => 28,  108 => 27,  105 => 26,  100 => 23,  91 => 20,  85 => 17,  81 => 16,  75 => 13,  72 => 12,  68 => 11,  64 => 9,  62 => 8,  57 => 6,  53 => 5,  47 => 2,  43 => 1,);
    }

    public function getSource()
    {
        return "{{ page.header }}
<section class=\"innerbanner\">{{ page.banner }}</section>
<section class=\"mainWrapper myAccountP formPage\" style=\"float:none\">  
  <div class=\"container\">
    {{ page.help }}
    {{ page.content }}
    <div id=\"content-area\" class=\"detailContent\">
      {% if(cur_page == ('landing_page')) %}
        <div class=\"managementLest newsletter_list\">
          <ul>
            {% for key,value in data_obj %}
              <li>
                <div class=\"imgSec\"><img src=\"{{ value.image}}\" alt=\"\"></div>
                <div class=\"contentSec\">
                  <div class=\"titleSec\">
                    <h4>{{ value.title }}</h4>
                    {{ value.desc | raw }}
                  </div>
                </div>
                <div class=\"btnSec arrowBtn\"><a class=\"button\" href=\"{{ value.alias }}\">Read more</a></div>
              </li>
            {% endfor %}
          </ul>
        </div>
      {% endif %}
      {% if logged_in %}
        {% if(cur_page == ('etdc')) %}
          <div class=\"innerContainer\">
            <h3>ETDC</h3>
          <div class=\"accountInfoSec\">
            <div class=\"formFealds\" rel=\"{{ data_obj.user_type | raw }}\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"{{ data_obj.first_name}}\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"{{ data_obj.last_name}}\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        {{ data_obj.batch_no | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        {{ data_obj.organisation | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" onblur=\"clearText(this)\" onfocus=\"clearText(this)\" value=\"{{ data_obj.designation | raw }}\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"{{ data_obj.mail | raw }}\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        {{ data_obj.country | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        {{ data_obj.state | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        {{ data_obj.city | raw }}
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"{{ data_obj.country_code | raw }}\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"{{ data_obj.mobile | raw }}\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" maxlength=\"100\">
                </li>
                <li>
                    <label>No of Persons <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\">No of Persons *</a>
                      <select class=\"numPers\">
                        <option value=\"\">Select</option>
                          {% for i in 1 .. 10 %}
                            <option value=\"{{ i }}\"> {{ i }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>No of rooms required <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"numRooms\">
                        <option value=\"\">Select</option>
                          {% for i in 1 .. 10 %}
                            <option value=\"{{ i }}\"> {{ i }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                
                <li>
                    <label>Food To Be Included <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox incFood\">
                    <div class=\"box\"><input type=\"radio\" name=\"incFood\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"incFood\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                <li>
                    <label>Food Preference <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox prefFood\">
                    <div class=\"box\"><input type=\"radio\" name=\"prefFood\" id=\"box5\" value=\"veg\" checked><label for=\"box5\"><span></span>Vegetarian</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"prefFood\" id=\"box6\" value=\"non-veg\"><label for=\"box6\"><span></span>Non-Vegetarian</label></div>
                  </div>
                </li>
                               
                <a class=\"button btnEtdc\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"{{ data_obj.token | raw}}\">
            </div>
          </div>
        </div>
      {% endif %}
      
      {% if(cur_page == ('wifi-access')) %}
        <div class=\"innerContainer\">
            <h3>Wi-fi Access</h3>
          <div class=\"accountInfoSec\">
            <div class=\"formFealds\" rel=\"{{ data_obj.user_type | raw }}\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"{{ data_obj.first_name}}\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"{{ data_obj.last_name}}\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        {{ data_obj.batch_no | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        {{ data_obj.organisation | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" value=\"{{ data_obj.designation | raw }}\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"{{ data_obj.mail | raw }}\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        {{ data_obj.country | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        {{ data_obj.state | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        {{ data_obj.city | raw }}
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"{{ data_obj.country_code | raw }}\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"{{ data_obj.mobile | raw }}\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Hour</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Minute</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Hour</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Minute</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" value=\"State Purpose *\" maxlength=\"100\">
                </li>
                <li>
                    <label>Need Access To Wi-fi <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox accWifi\">
                    <div class=\"box\"><input type=\"radio\" name=\"accWifi\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"accWifi\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                               
                <a class=\"button btnWifi\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"{{ data_obj.token | raw}}\">
            </div>
          </div>
        </div>
      {% endif %}      
      {% if(cur_page == ('sac')) %}
        <div class=\"innerContainer\">
            <h3>Students' Activity Centre</h3>
          <div class=\"accountInfoSec\">
            <div class=\"formFealds\" rel=\"{{ data_obj.user_type | raw }}\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"{{ data_obj.first_name}}\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"{{ data_obj.last_name}}\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        {{ data_obj.batch_no | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        {{ data_obj.organisation | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" value=\"{{ data_obj.designation | raw }}\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"{{ data_obj.mail | raw }}\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        {{ data_obj.country | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        {{ data_obj.state | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        {{ data_obj.city | raw }}
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"{{ data_obj.country_code | raw }}\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"{{ data_obj.mobile | raw }}\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Hour</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Minute</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Hour</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Minute</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" maxlength=\"100\">
                </li>
                <li>
                    <label>Need Access To SAC <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox accSac\">
                    <div class=\"box\"><input type=\"radio\" name=\"accSac\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"accSac\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                               
                <a class=\"button btnSac\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"{{ data_obj.token | raw}}\">
            </div>
          </div>
        </div>
      {% endif %}
      
      {% if(cur_page == ('students-mess')) %}
        <div class=\"innerContainer\">
            <h3>Students' Mess</h3>
          <div class=\"accountInfoSec\">
            <div class=\"formFealds\" rel=\"{{ data_obj.user_type | raw }}\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"{{ data_obj.first_name}}\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"{{ data_obj.last_name}}\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        {{ data_obj.batch_no | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        {{ data_obj.organisation | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" onblur=\"clearText(this)\" onfocus=\"clearText(this)\" value=\"{{ data_obj.designation | raw }}\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"{{ data_obj.mail | raw }}\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        {{ data_obj.country | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        {{ data_obj.state | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        {{ data_obj.city | raw }}
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"{{ data_obj.country_code | raw }}\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"{{ data_obj.mobile | raw }}\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" maxlength=\"100\">
                </li>
                <li>
                    <label>Arrange Food At Students' Mess <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox arrFood\">
                    <div class=\"box\"><input type=\"radio\" name=\"arrFood\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"arrFood\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                <li>
                    <label>No of Persons <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"noPers\">
                        <option value=\"\">Select</option>
                          {% for i in 0 .. 10 %}
                            <option value=\"{{ i }}\">{{ i }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <a class=\"button btnMess\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"{{ data_obj.token | raw}}\">
            </div>
          </div>
        </div>
      {% endif %}
      
      {% if(cur_page == ('library')) %}
        <div class=\"innerContainer\">
          <div class=\"accountInfoSec\">
              <h3>Library</h3>
            <div class=\"formFealds\" rel=\"{{ data_obj.user_type | raw }}\">
              <ul>
                <li>
                    <label>First Name</label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"{{ data_obj.first_name}}\">
                </li>
                <li>
                    <label>Last Name</label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"{{ data_obj.last_name}}\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        {{ data_obj.batch_no | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        {{ data_obj.organisation | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" onblur=\"clearText(this)\" onfocus=\"clearText(this)\" value=\"{{ data_obj.designation | raw }}\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"{{ data_obj.mail | raw }}\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        {{ data_obj.country | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        {{ data_obj.state | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        {{ data_obj.city | raw }}
                      </select>
                    </div>
                  </div>
                </li>

                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"{{ data_obj.country_code | raw }}\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"{{ data_obj.mobile | raw }}\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>Arrival Date <span class=\"required\">*</span></label>
                    <input class=\"arrDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrHr\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"arrMin\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Departure Date <span class=\"required\">*</span></label>
                    <input class=\"depDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>Hour <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depHr\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 23 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Minute <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"depMin\">
                        <option value=\"\">Select</option>
                          {% for i in 00 .. 59 %}
                            <option value=\"{{ i }}\"> {{ \"%02d\"|format(i) }}</option>
                          {% endfor %}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Purpose Of Visit <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox purpVisit\">
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box1\" value=\"Official\" checked><label for=\"box1\"><span></span> Official</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"purpVisit\" id=\"box2\" value=\"Personal\"><label for=\"box2\"><span></span> Personal</label></div>
                  </div>
                </li>
                <li>
                    <label>State Purpose <span class=\"required\">*</span></label>
                  <input type=\"text\" class=\"purpose\" maxlength=\"100\">
                </li>
                <li>
                    <label>Need Access To Library <span class=\"required\">*</span></label>
                  <div class=\"customCheckBox accLib\">
                    <div class=\"box\"><input type=\"radio\" name=\"accLib\" id=\"box3\" value=\"yes\" checked><label for=\"box3\"><span></span> Yes</label></div>
                    <div class=\"box\"><input type=\"radio\" name=\"accLib\" id=\"box4\" value=\"no\"><label for=\"box4\"><span></span> No</label></div>
                  </div>
                </li>
                <a class=\"button btnLibr\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"{{ data_obj.token | raw}}\">
            </div>
          </div>
        </div>
      {% endif %}
      {% if(cur_page == ('MDP')) %}
        <div class=\"innerContainer\">
          <div class=\"accountInfoSec\">
            <h3>Management Development Programme</h3>
            <div class=\"formFealds\" rel=\"{{ data_obj.user_type | raw }}\">
              <ul>
                <li>
                    <label>First Name <span class=\"required\">*</span></label>
                    <input maxlength=\"25\" class=\"fName\" type=\"text\" value=\"{{ data_obj.first_name}}\">
                </li>
                <li>
                    <label>Last Name <span class=\"required\">*</span></label>
                    <input maxlength=\"25\" class=\"lName\" type=\"text\" value=\"{{ data_obj.last_name}}\">
                </li>
                <li>
                    <label>Batch Number <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink {{ data_obj.batch_no | raw }}\" href=\"javascript:;\">{{ data_obj.batch_no }}</a>
                      <select class=\"batchNo\">
                        <option value=\"\">Select</option>
                        {{ data_obj.batch_no | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Organisation <span class=\"required\">*</span></label>
                  <div class=\"customSelect\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"organisation\">
                        <option value=\"\">Select</option>
                        {{ data_obj.organisation | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Designation <span class=\"required\">*</span></label>
                    <input class=\"design\" type=\"text\" value=\"{{ data_obj.designation | raw }}\">
                </li>
                <li>
                    <label>Email Id <span class=\"required\">*</span></label>
                    <input maxlength=\"100\" class=\"email\" type=\"text\" value=\"{{ data_obj.mail | raw }}\">
                </li>
                <li>
                  <label>Country <span class=\"required\">*</span></label>
                  <div class=\"customSelect cntryList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"country countries\">
                        <option value=\"\">Select</option>
                        {{ data_obj.country | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>State <span class=\"required\">*</span></label>
                  <div class=\"customSelect stateList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"states\">
                        <option value=\"\">Select</option>
                        {{ data_obj.state | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                  <label>City <span class=\"required\">*</span></label>
                  <div class=\"customSelect cityList\">
                    <div class=\"dropdownWrap\">
                      <a class=\"shortDropLink\" href=\"javascript:;\"></a>
                      <select class=\"cities\">
                        <option value=\"\">Select</option>
                        {{ data_obj.city | raw }}
                      </select>
                    </div>
                  </div>
                </li>
                <li>
                    <label>Mobile Number <span class=\"required\">*</span></label>
                  <div class=\"phoneNo\">
                    <input class=\"telCode\" type=\"text\" value=\"+91\" maxlength=\"5\" id=\"{{ data_obj.country_code | raw }}\" readonly>
                    <input class=\"mobileNo\" type=\"tel\"  value=\"{{ data_obj.mobile | raw }}\" maxlength=\"15\">
                  </div>
                </li>
                <li>
                    <label>MDP Name <span class=\"required\">*</span></label>
                    <input class=\"mdpName\" type=\"text\" maxlength=\"100\">
                </li>
                <li>
                    <label>MDP Start Date <span class=\"required\">*</span></label>
                    <input class=\"startDate\" type=\"text\" maxlength=\"10\">
                </li>
                <li>
                    <label>MDP End Date <span class=\"required\">*</span></label>
                    <input class=\"enDate\" type=\"text\" value=\"\" maxlength=\"10\">
                </li>
                <li>
                    <label>MDP Query <span class=\"required\">*</span></label>
                    <textarea class=\"mdpQry\" type=\"text\" maxlength=\"100\"></textarea>
                </li>
        
                <a class=\"button btnMdp\" href=\"javascript:;\">Apply</a>
              </ul>
              <input type=\"hidden\" id=\"csrftoken\" value=\"{{ data_obj.token | raw}}\">
            </div>
          </div>
        </div>
        {% endif %}
      {% else %}
        <div class=\"detailContent trigger\">
          <div class=\"managementLest\">
            <h2>You need to log in to view this page</h2>
          </div>
        </div>
      {% endif %}
    </div>
  </div>

</section>

{% if page.footer|render %}
  <footer><div class=\"container footer-container\">{{ page.footer }}</div></footer>
{% endif %}


";
    }
}
