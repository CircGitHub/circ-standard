<?php defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::model("file_set");

class CircStandardPackage extends Package
{

    protected $pkgHandle = 'circ_standard';
    protected $appVersionRequired = '5.5.2.1';
    protected $pkgVersion = '0.00.06';
    private $pageTypes = array();
    private $attributeTypes = array();

    protected $defaultPageType;

    public function getPackageDescription()
    {
        return t('Standard Circ Package, reusable elements for Circ projects');
    }

    public function getPackageName()
    {
        return t('Standard Circ Package');
    }

    public function on_start()
    {
        $uh = Loader::helper('concrete/urls');
        define('CIRC_STANDARD_TOOLS_DIR', __DIR__ . '/tools');
        define('CIRC_STANDARD_PACKAGE_DIR', __DIR__);
        define('CIRC_STANDARD_IMAGES_DIR', __DIR__ . '/images/');
        $this->registerAutoloader($this->pkgHandle);
    }

    private function registerAutoloader($namespace)
    {
        $autoloader = function ($className) use ($namespace) {
            if ($namespace == array_shift(explode('\\', $className))) {
                require __DIR__ . '/libraries/' . str_replace('\\', '/', $className) . '.php';
                return true;
            }
            return false;
        };
        spl_autoload_register($autoloader);
    }


    /**
     * Get the package object; if it hasn't been instantiated yet, load it.
     * @return Package
     */
    private function packageObject()
    {
        if (!($this->packageObj instanceof Package)) {
            $this->packageObj = Package::getByHandle($this->pkgHandle);
        }
        return $this->packageObj;
    }


    /**
     * Get the config object, specfically associated w/ this package.
     * @return Config
     */
    private function packageConfig()
    {
        if (!($this->packageConfig instanceof Config)) {
            $this->packageConfig = new Config();
            $this->packageConfig->setPackageObject($this->packageObject());
        }
        return $this->packageConfig;
    }

    /**
     * @param string $type
     *
     * @return AttributeType
     * @throws InvalidArgumentException
     */
    private function getAttributeType($type)
    {
        if (!array_key_exists($type, $this->attributeTypes)) {
            $attributeType = AttributeType::getByHandle($type);
            if ($attributeType->atID) {
                $this->attributeTypes[$type] = $attributeType;
            } else {
                throw new InvalidArgumentException("$type is not a valid attribute type.");
            }
        }
        return $this->attributeTypes[$type];
    }


    public function uninstall()
    {
        parent::uninstall();
    }


    public function upgrade()
    {
        parent::upgrade();
        $this->dontResetupPages = true; // flag for page setup
        $this->installAndUpdate();
    }


    public function install()
    {
        $this->packageObj = parent::install();
        $this->installAndUpdate();
    }


    /**
     * Instead of using the install() and update() methods independently, we use
     * one method to run everything.
     */
    private function installAndUpdate()
    {
        $this->installBlockTypes()
            ->installFileSets();
    }


    private function installBlockTypes()
    {
        $this->createBlockType('bucket');
        return $this;
    }

    private function createBlockType($handle)
    {
        $block = BlockType::getByHandle($handle);
        if (!(is_object($block))) {
            $block = BlockType::installBlockTypeFromPackage($handle, $this->packageObject());
        }
        return $block;
    }

    private function installFileSets()
    {
        $this->installFileSet('buckets');
    }

    private function installFileSet($name, $type = FileSet::TYPE_PRIVATE)
    {
        if (!FileSet::getByName($name)) {
            FileSet::createAndGetSet($name, $type);
        }
    }


    private function setupThemeAndPageTypes()
    {

    }

    private function createPageType($handle, $name, $icon = 'main.png')
    {
        $this->pageTypes[$handle] = CollectionType::getByHandle($handle);
        if (!$this->pageTypes[$handle] instanceof CollectionType) {
            $this->pageTypes[$handle] = CollectionType::add(array('uID' => 1, 'ctHandle' => $handle, 'ctName' => $name, 'ctIcon' => $icon), $this->packageObject());
        }
        return $this->pageTypes[$handle];
    }

    /**
     * Install any jobs
     * @return LintonPackage
     */
    private function setupJobs()
    {

        return $this;
    }
}
