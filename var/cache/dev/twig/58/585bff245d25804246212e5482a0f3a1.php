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

/* homepage.html.twig */
class __TwigTemplate_470e4f9ae6fa3c25be2abb6028087c70 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "homepage.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "homepage.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "homepage.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 4
        echo "    <div class=\"jumbotron-img p-2 mb-2\">
        <div class=\"container\">
            <h1 class=\"display-4\">Your Questions Answered</h1>
            <div class=\"lead\">And even answers for those questions you didn't think to ask!</div>
        </div>
    </div>
    <div class=\"container\">
        <div class=\"row mb-3\">
            <div class=\"col\">
                <button class=\"btn btn-question\">Ask a Question</button>
            </div>
        </div>
            ";
        // line 16
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["products"]) || array_key_exists("products", $context) ? $context["products"] : (function () { throw new RuntimeError('Variable "products" does not exist.', 16, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 17
            echo "                <strong>Product Title:</strong> <a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_product_page", ["slug" => twig_get_attribute($this->env, $this->source, $context["product"], "slug", [], "any", false, false, false, 17)]), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["product"], "title", [], "any", false, false, false, 17), "html", null, true);
            echo " </a> <br>
                <strong>Product Description:</strong>  ";
            // line 18
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["product"], "description", [], "any", false, false, false, 18), "html", null, true);
            echo " <br>
                <br><br><br><br>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 21
        echo "
";
        // line 54
        echo "
";
        // line 57
        echo "    </div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

    }

    public function getTemplateName()
    {
        return "homepage.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  108 => 57,  105 => 54,  102 => 21,  93 => 18,  86 => 17,  82 => 16,  68 => 4,  58 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}

{% block body %}
    <div class=\"jumbotron-img p-2 mb-2\">
        <div class=\"container\">
            <h1 class=\"display-4\">Your Questions Answered</h1>
            <div class=\"lead\">And even answers for those questions you didn't think to ask!</div>
        </div>
    </div>
    <div class=\"container\">
        <div class=\"row mb-3\">
            <div class=\"col\">
                <button class=\"btn btn-question\">Ask a Question</button>
            </div>
        </div>
            {% for product in products %}
                <strong>Product Title:</strong> <a href=\"{{ path('app_product_page', { slug: product.slug } ) }}\">{{ product.title }} </a> <br>
                <strong>Product Description:</strong>  {{ product.description }} <br>
                <br><br><br><br>
            {% endfor %}

{#            {% for question in pager %}#}
{#                <div class=\"col-12 mb-3\">#}
{#                    <div style=\"box-shadow: 2px 3px 9px 4px rgba(0,0,0,0.04);\">#}
{#                        <div class=\"q-container p-4\">#}
{#                            <div class=\"row\">#}
{#                                <div class=\"col-2 text-center\">#}
{#                                    <img src=\"{{ asset('images/tisha.png') }}\" width=\"100\" height=\"100\"  alt=\"Tisha avatar\">#}
{#                                    <div class=\"vote-arrows vote-arrows-alt flex-fill pt-2\" style=\"min-width: 90px;\">#}
{#                                        <span>{{ question.votesString}} votes</span>#}
{#                                    </div>#}
{#                                    {% for questionTag in question.questionTags %}#}
{#                                        <span class=\"badge rounded-pill bg-light text-dark\">{{ questionTag.tag.name }}</span>#}
{#                                    {% endfor %}#}
{#                                </div>#}
{#                                <div class=\"col\">#}
{#                                    <a class=\"q-title\" href=\"{{ path('app_question_show', { slug: question.slug }) }}\"><h2>{{ question.name }}</h2></a>#}
{#                                    <div class=\"q-display p-3\">#}
{#                                        <i class=\"fa fa-quote-left mr-3\"></i>#}
{#                                        <p class=\"d-inline\">{{ question.question|parse_markdown }}</p>#}
{#                                        <p class=\"pt-4\"><strong>--Tisha</strong></p>#}
{#                                    </div>#}
{#                                </div>#}
{#                            </div>#}
{#                        </div>#}
{#                        <a class=\"answer-link\" href=\"{{ path('app_question_show', { slug: question.slug }) }}\" style=\"color: #fff;\">#}
{#                            <p class=\"q-display-response text-center p-3\">#}
{#                                <i class=\"fa fa-magic magic-wand\"></i> {{ question.approvedAnswers|length}} answers#}
{#                            </p>#}
{#                        </a>#}
{#                    </div>#}
{#                </div>#}
{#            {% endfor %}#}

{#            {{ pagerfanta(pager) }}#}
{#        </div>#}
    </div>
{% endblock %}

", "homepage.html.twig", "C:\\xampp\\htdocs\\ecommerce\\tablouri\\templates\\homepage.html.twig");
    }
}
