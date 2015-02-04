<?php
/**
 * Created by PhpStorm.
 * User: Giel
 * Date: 12-01-15
 * Time: 19:43
 * File: Item.php
 */
 
namespace Boom\Items;

/**
 * Interface Base_Interface
 * @package Boom\Items
 */
interface Base_Interface
{
    public function getCode();
    public function getName();
    public function getOptions(array $defaults);
}

/**
 * Class Base
 * @package Boom\Items
 */
abstract class Base implements Base_Interface
{
    /* @var $code String */
    protected $code;
    
    /* @var $name String */
    protected $name;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Get the code
     * @return String
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the name
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the options
     * @param array $defaults
     * @return array
     */
    public function getOptions(array $defaults = array())
    {
        // Stub
        return array();
    }

    /**
     * Render the SVG data
     * @param array $options
     * @return String
     */
    abstract public function renderSvg(array $options = array());

    /**
     * Initialisation stub
     */
    abstract protected function init();
    
    /**
     * Way of changing options prior before rendering the SVG
     * @param $options
     * @return array
     */
    protected function manipulateOptionsBeforeRendering(array $options = array())
    {
        // Stub
        return $options;
    }

    /**
     * Get default options
     * @return array
     */
    protected function getDefaultOptions()
    {
        $options = array();
        foreach($this->getOptions() as $option)
        {
            $options[$option['name']] = $option['default'];
        }
        return $options;
    }
}