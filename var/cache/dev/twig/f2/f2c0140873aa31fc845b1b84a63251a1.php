<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* base.html.twig */
class __TwigTemplate_cc968665991ccae1d97eee810bb72649 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'stylesheets' => [$this, 'block_stylesheets'],
            'javascripts' => [$this, 'block_javascripts'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "base.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "base.html.twig"));

        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <title>";
        // line 5
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
    ";
        // line 6
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 10
        echo "    ";
        $this->displayBlock('javascripts', $context, $blocks);
        // line 14
        echo "</head>
<body>
<nav class=\"navbar navbar-expand-lg navbar-light bg-light px-1\">
    <div class=\"container-fluid\">
        <a class=\"navbar-brand\" href=\"";
        // line 18
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_homepage");
        echo "\">
            <p class=\"pl-2 d-inline font-weight-bold\" style=\"color: #444;\">
                Cauldron Overflow</p>
        </a>

        <button class=\"navbar-toggler\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbar-collapsable\" aria-controls=\"navbarTogglerDemo01\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>

        <div class=\"collapse navbar-collapse\" id=\"navbar-collapsable\">
            <ul class=\"navbar-nav me-auto mb-2 mb-lg-0\">
";
        // line 32
        echo "            </ul>
            ";
        // line 33
        if ($this->extensions['Symfony\Bridge\Twig\Extension\SecurityExtension']->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            // line 34
            echo "                <div class=\"d-inline-flex \">
                    <a href=\"";
            // line 35
            echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_cart");
            echo "\" class=\"m-2\">
                        <i class=\"fa-solid fa-cart-shopping\"></i>
                    </a>
                    <p class=\"items_number m-2\">1230</p>
                </div>



            <div class=\"dropdown\">
                <button
                        class=\"dropdown-toggle btn\"
                        type=\"button\"
                        id=\"user-dropdown\"
                        data-bs-toggle=\"dropdown\"
                        aria-expanded=\"false\"
                >
                    <img
                            src=\"";
            // line 52
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 52, $this->source); })()), "user", [], "any", false, false, false, 52), "avatarUri", [], "any", false, false, false, 52), "html", null, true);
            echo "\"
                            alt=\"";
            // line 53
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 53, $this->source); })()), "user", [], "any", false, false, false, 53), "displayName", [], "any", false, false, false, 53), "html", null, true);
            echo " Avatar\">
                </button>
                <ul class=\"dropdown-menu dropdown-menu-end\" aria-labelledby=\"user-dropdown\">

                    ";
            // line 57
            if ($this->extensions['Symfony\Bridge\Twig\Extension\SecurityExtension']->isGranted("ROLE_PREVIOUS_ADMIN")) {
                // line 58
                echo "                        <li>
                            <a class=\"dropdown-item\" href=\"";
                // line 59
                echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_homepage", ["_switch_user" => "_exit"]);
                // line 61
                echo "\">Exit Impersonation</a>
                        </li>
                    ";
            } else {
                // line 64
                echo "                        <li>
                            <a class=\"dropdown-item\" href=\"";
                // line 65
                echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_logout");
                echo "\">Log Out</a>
                        </li>
                        <li>
                            <a class=\"nav-link text-black-50 m-2\" href=\"";
                // line 68
                echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_cart");
                echo "\">Cart </a>
                        </li>
                    ";
            }
            // line 71
            echo "
                </ul>
            </div>
            ";
        } else {
            // line 75
            echo "            <a class=\"nav-link text-black-50 m-2\" href=\"";
            echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_login");
            echo "\">Log In </a>
            <a href=\"";
            // line 76
            echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_register");
            echo "\" class=\"btn btn-dark m-2\">Sign up</a>
            ";
        }
        // line 78
        echo "        </div>
    </div>
</nav>
";
        // line 81
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 81, $this->source); })()), "flashes", [0 => "success"], "method", false, false, false, 81));
        foreach ($context['_seq'] as $context["_key"] => $context["flash"]) {
            // line 82
            echo "    <div class=\"alert alert-success\">
        ";
            // line 83
            echo twig_escape_filter($this->env, $context["flash"], "html", null, true);
            echo "
    </div>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['flash'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 86
        $this->displayBlock('body', $context, $blocks);
        // line 87
        echo "<footer class=\"mt-5 p-3 text-center\">
    <i class=\"fa-solid fa-bridge-suspension\"></i>
    Made by <i style=\"color: red;\" class=\"far fa-heart\"></i> <i class=\"fa-solid fa-heart\"></i>Steve Developer
</footer>
</body>
</html>
";
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 5
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        echo "Welcome!";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 6
    public function block_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 7
        echo "        <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Spartan&display=swap\">
        <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css\" integrity=\"sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />        ";
        // line 8
        echo $this->extensions['Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension']->renderWebpackLinkTags("app");
        echo "
    ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 10
    public function block_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 11
        echo "        ";
        echo $this->extensions['Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension']->renderWebpackScriptTags("app");
        echo "
        <script src=\"https://code.jquery.com/jquery-3.6.0.js\" integrity=\"sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=\" crossorigin=\"anonymous\"></script>
    ";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    // line 86
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  271 => 86,  257 => 11,  247 => 10,  235 => 8,  232 => 7,  222 => 6,  203 => 5,  187 => 87,  185 => 86,  176 => 83,  173 => 82,  169 => 81,  164 => 78,  159 => 76,  154 => 75,  148 => 71,  142 => 68,  136 => 65,  133 => 64,  128 => 61,  126 => 59,  123 => 58,  121 => 57,  114 => 53,  110 => 52,  90 => 35,  87 => 34,  85 => 33,  82 => 32,  68 => 18,  62 => 14,  59 => 10,  57 => 6,  53 => 5,  47 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html>
<head>
    <meta charset=\"UTF-8\">
    <title>{% block title %}Welcome!{% endblock %}</title>
    {% block stylesheets %}
        <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Spartan&display=swap\">
        <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css\" integrity=\"sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />        {{ encore_entry_link_tags('app') }}
    {% endblock %}
    {% block javascripts %}
        {{ encore_entry_script_tags('app') }}
        <script src=\"https://code.jquery.com/jquery-3.6.0.js\" integrity=\"sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=\" crossorigin=\"anonymous\"></script>
    {% endblock %}
</head>
<body>
<nav class=\"navbar navbar-expand-lg navbar-light bg-light px-1\">
    <div class=\"container-fluid\">
        <a class=\"navbar-brand\" href=\"{{ path('app_homepage') }}\">
            <p class=\"pl-2 d-inline font-weight-bold\" style=\"color: #444;\">
                Cauldron Overflow</p>
        </a>

        <button class=\"navbar-toggler\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbar-collapsable\" aria-controls=\"navbarTogglerDemo01\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>

        <div class=\"collapse navbar-collapse\" id=\"navbar-collapsable\">
            <ul class=\"navbar-nav me-auto mb-2 mb-lg-0\">
{#                <li class=\"nav-item\">#}
{#                    <a class=\"nav-link\" href=\"{{ path('app_popular_answers') }}\">Answers</a>#}
{#                </li>#}
            </ul>
            {% if is_granted('IS_AUTHENTICATED_REMEMBERED') %}
                <div class=\"d-inline-flex \">
                    <a href=\"{{ path('app_cart') }}\" class=\"m-2\">
                        <i class=\"fa-solid fa-cart-shopping\"></i>
                    </a>
                    <p class=\"items_number m-2\">1230</p>
                </div>



            <div class=\"dropdown\">
                <button
                        class=\"dropdown-toggle btn\"
                        type=\"button\"
                        id=\"user-dropdown\"
                        data-bs-toggle=\"dropdown\"
                        aria-expanded=\"false\"
                >
                    <img
                            src=\"{{ app.user.avatarUri }}\"
                            alt=\"{{ app.user.displayName }} Avatar\">
                </button>
                <ul class=\"dropdown-menu dropdown-menu-end\" aria-labelledby=\"user-dropdown\">

                    {% if is_granted('ROLE_PREVIOUS_ADMIN') %}
                        <li>
                            <a class=\"dropdown-item\" href=\"{{ path('app_homepage' , {
                                '_switch_user':'_exit'
                            }) }}\">Exit Impersonation</a>
                        </li>
                    {% else %}
                        <li>
                            <a class=\"dropdown-item\" href=\"{{ path('app_logout') }}\">Log Out</a>
                        </li>
                        <li>
                            <a class=\"nav-link text-black-50 m-2\" href=\"{{ path('app_cart') }}\">Cart </a>
                        </li>
                    {% endif %}

                </ul>
            </div>
            {% else %}
            <a class=\"nav-link text-black-50 m-2\" href=\"{{ path('app_login') }}\">Log In </a>
            <a href=\"{{ path('app_register') }}\" class=\"btn btn-dark m-2\">Sign up</a>
            {% endif %}
        </div>
    </div>
</nav>
{% for flash in app.flashes('success') %}
    <div class=\"alert alert-success\">
        {{ flash }}
    </div>
{% endfor %}
{% block body %}{% endblock %}
<footer class=\"mt-5 p-3 text-center\">
    <i class=\"fa-solid fa-bridge-suspension\"></i>
    Made by <i style=\"color: red;\" class=\"far fa-heart\"></i> <i class=\"fa-solid fa-heart\"></i>Steve Developer
</footer>
</body>
</html>
", "base.html.twig", "C:\\xampp\\htdocs\\ecommerce\\tablouri\\templates\\base.html.twig");
    }
}
