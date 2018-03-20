# Drupal 8 module for Query Path Twig Helper

Since Drupal 8 renders HTML with tons of its twig templates,
it's always painful to make HTML structure to be what you want,
then QueryPath is super useful for this.

1. Enable this
2. Check the examples in src/TwigExtension.php, then create your own helper functions there.
3. You can do this in any twig templates:

```twig
    {% set ct = page.content %}
    {{ your_helper_function_in_module(ct) }}
```