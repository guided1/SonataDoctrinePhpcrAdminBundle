<?php

/*
 * This file is part of sonata-project.
 *
 * (c) 2010 Thomas Rabaix
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\DoctrinePHPCRAdminBundle\Twig\Extension;

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Filter\FilterInterface;
use Sonata\AdminBundle\Admin\NoValueException;
use PHPCR\NodeInterface;

use Symfony\Component\Form\FormView;

class SonataDoctrinePHPCRAdminExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'sonata_doctrine_phpcr_admin';
    }

    /**
     * render a list element from the FieldDescription
     *
     * @param $object
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     * @param array $params
     * @return string
     */
    public function renderListElement($object, FieldDescriptionInterface $fieldDescription, $params = array())
    {
        $template = $this->getTemplate($fieldDescription, 'SonataAdminBundle:CRUD:base_list_field.html.twig');

        return $this->output($fieldDescription, $template, array_merge($params, array(
            'admin'  => $fieldDescription->getAdmin(),
            'object' => $object,
            'value'  => $this->getValueFromFieldDescription($object, $fieldDescription),
            'field_description' => $fieldDescription
        )));
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'render_node_property'     => new \Twig_Filter_Method($this, 'renderNodeProperty', array('is_safe' => array('html'))),
            'render_node_path'     => new \Twig_Filter_Method($this, 'renderNodePath', array('is_safe' => array('html'))),
        );
    }


    /**
     * Renders a property of a node
     *
     * @param \PHPCR\NodeInterface $node
     * @param string $property
     * @return string representing the property
     */
    public function renderNodeProperty(NodeInterface $node, $property)
    {
        return $node->getProperty($property)->getString();
    }

    /**
     * Renders a property of a node
     *
     * @param \PHPCR\NodeInterface $node
     * @return string the path
     */
    public function renderNodePath(NodeInterface $node)
    {
        return $node->getPath();
    }
}

