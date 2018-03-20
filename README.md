# Drupal 8 Query Path helper module

### How to use
You can do this in any twig templates:
- Check the 2 examples in src/TwigExtension.php.
```twig
    {% set ct = page.content %}
    {{ your_helper_function_in_module(ct) }}
```