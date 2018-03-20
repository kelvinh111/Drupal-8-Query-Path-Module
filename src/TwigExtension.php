<?php
namespace Drupal\dom_tool;

require 'modules/custom/dom_tool/querypath/src/qp.php';

/**
 * Class DefaultService.
 *
 * @package Drupal\dom_tool
 */
class TwigExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     * This function must return the name of the extension. It must be unique.
     */
    public function getName()
    {
        return 'dom_tool';
    }

    /**
     * Register extension functions below
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('homeslide', array($this, 'homeslide'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('body_menu', array($this, 'body_menu'), array('is_safe' => array('html'))),
        );
    }

    /*
    Example 1
     */
    public function homeslide($content)
    {
        $html_obj = drupal_render($content);
        $html_arr = (array) $html_obj;
        $html     = array_pop($html_arr);
        $qp       = html5qp($html);

        // add swiper selector
        $qp->find('.field--name-field-paragraphs')->attr('id', 'homeslide')->addClass('swiper-container')->wrapInner('<div class="swiper-wrapper"></div>');

        $qp->find('.swiper-wrapper > .field__item')->addClass('swiper-slide swiper-no-swiping para-section')->each(function ($k, $v) {
            $fi = html5qp($v);
            $id = $fi->find('.field--name-field-hs-id .field__item')->text();
            $fi->attr('id', $id)->attr('data-hash', substr($id, 3));
            $cls = explode(' ', $fi->find('.paragraph')->attr('class'));
            foreach($cls as $k => $v) {
                if(substr($v, 0, 27) == 'paragraph--type--homeslide-') {
                    // ksm(substr($v, 27));
                    $fi->addClass('hs-' . substr($v, 27));
                }
            }
            // homeslide content has layout (left or right)
            if ($fi->find('.field--name-field-hs-layout')->length) {
                $layout = $fi->find('.field--name-field-hs-layout')->text();
                $fi->addClass('hs-layout-' . $layout);
            }
        });

        // make images to be background images
        $qp->find('#homeslide .field[class*="image"]:not(.field--name-field-hs-contact-map, .field--name-field-hs-welcome-logo) img')->each(function ($k, $v) {
            $img = html5qp($v);
            $url = $img->attr('src');
            $img->parent()->css('background-image', 'url(' . $url . ')');
            $img->remove();
        });

        // wrap content without image for easier theming
        $qp->find('#homeslide .para-section')->each(function ($k, $v) {
            $slide = html5qp($v);
            if ($slide->is('#hs-welcome')) {
                $slide->find('.field:not(.field--name-field-hs-welcome-image)')->wrapAll('<div class="slide-container"><div class="slide-board"></div></div>');
                $slide->append('<div class="swiper-button-next"></div>');
            } else if ($slide->is('#hs-contact-us')) {
                $slide->find('.field--name-field-hs-contact-title, .field--name-field-hs-contact-body')->wrapAll('<div class="slide-container"><div class="slide-board"></div></div>');
                $slide->find('.slide-board .field:not([class*="title"])')->wrapAll('<div class="slide-content"></div>');


            } else {
                $slide->find('.field:not(.field--type-image)')->wrapAll('<div class="slide-container"><div class="slide-board"></div></div>');
                $slide->find('.slide-board .field:not([class*="title"])')->wrapAll('<div class="slide-content"></div>');

            }

            if ($slide->not('#hs-contact')) {
                $slide->find('.slide-board')->append('<div class="swiper-button-next"></div>');
            }
        });

        return $qp->writeHTML5();
    }

    /*
    Example 1
     */
    public function body_menu($content)
    {
        $html_obj = drupal_render($content);
        $html_arr = (array) $html_obj;
        $html     = array_pop($html_arr);
        $qp       = html5qp($html);
        $qp->find('.top-level')->each(function ($i, $v) {
            $tmp   = html5qp($v);
            $title = $tmp->attr('title');
            $tmp->wrapInner('<div class="body-menu-text"></div>');
            $tmp->append('<div class="body-menu-desc">' . $title . '</div>');
        });

        return $qp->writeHTML5();
    }
}
