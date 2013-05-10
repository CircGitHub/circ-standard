<?php defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::library("file/importer");
Loader::model("file_set");
Loader::model("file");

class BucketBlockController extends BlockController {
	
	protected $btTable = "btCircBucket";
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "500";
    protected $btName = 'Circ Standard Bucket';
    protected $btDescription = 'Create Content Buckets';

    public function save(array $args)
    {
        $width = (!empty($args['copy_width']) && !is_numeric($args['copy_width']));
        $top = (!empty($args['copy_top']) && !is_numeric($args['copy_top']));
        $bottom = (!empty($args['copy_bottom']) && !is_numeric($args['copy_bottom']));
        $left = (!empty($args['copy_left']) && !is_numeric($args['copy_left']));
        $right = (!empty($args['copy_right']) && !is_numeric($args['copy_right']));
        if($width || $top || $bottom || $left || $right)
        {
            throw new Exception('All copy style fields must be numeric.');
        }
        if(!empty($args['copy_left']) && !empty($args['copy_right'])) {
            throw new Exception('Only a left or right css style is allowed, not both.');
        }
        if (!empty($args['copy_top']) && !empty($args['copy_bottom'])) {
            throw new Exception('Only a top or bottom css style is allowed, not both.');
        }

        if(preg_match('/[^a-zA-Z0-9_-]/', $args['css_id']) || preg_match('/[^ a-zA-Z0-9_-]/', $args['css_class'])) {
            throw new Exception('The CSS ID and Class can only contain numbers, letters, dashes and underscores. Classes can also contain spaces.');
        }


        if (!empty($_FILES['background']['tmp_name'])) {
            if (!empty($this->record->image_id)) {
                File::getById($this->record->image_id)->delete();
            }
            $file = $this->addFile($_FILES['background'], 'buckets');
            $args['image_id'] = $file->fID;
        } elseif($this->record->image_id != '') {
            $args['image_id'] = $this->record->image_id;
        }
        $args = array_map(function($value) {return $value === '' ? null : $value;}, $args);


        parent::save($args);
    }

    private function addFile($file, $fileset)
    {
        $importer = new FileImporter;
        $file = $importer->import($file['tmp_name'], $file['name']);
        FileSet::getByName($fileset)->addFileToSet($file);
        return $file;
    }

    public function view()
    {
        $containerStyle = '';
        if(!empty($this->record->image_id)) {
            $containerStyle = 'background: url(' . File::getRelativePathFromID($this->record->image_id) . ') no-repeat;';
        }

        $copyWidth = $this->record->copy_width;
        $copyTop = $this->record->copy_top;
        $copyBottom = $this->record->copy_bottom;
        $copyLeft = $this->record->copy_left;
        $copyRight = $this->record->copy_right;
        $copyStyles = array();
        if (!empty($copyWidth)) {
            $copyStyles['width'] = $copyWidth . 'px';
        }
        if($copyTop != '') {
            $copyStyles['top'] = $copyTop . 'px';
            $copyStyles['position'] = 'absolute';
        } elseif($copyBottom != '') {
            $copyStyles['bottom'] = $copyBottom . 'px';
            $copyStyles['position'] = 'absolute';
        }
        if ($copyLeft != '') {
            $copyStyles['left'] = $copyLeft . 'px';
            $copyStyles['position'] = 'absolute';
        } elseif ($copyRight != '') {
            $copyStyles['right'] = $copyRight . 'px';
            $copyStyles['position'] = 'absolute';
        }
        $copyStyle = '';
        if(array_key_exists('position', $copyStyles)) {
            $containerStyle .= 'position: relative;';
        }
        foreach($copyStyles as $attribute => $value) {
            $copyStyle .= $attribute . ': ' . $value . ';';
        }

        $this->set('heading', $this->record->heading);
        $this->set('cssId', !empty($this->record->css_id) ? $this->record->css_id : 'bucket_' . $this->record->bID);
        $this->set('cssClass', !empty($this->record->css_class) ? 'circ_standard_bucket ' . $this->record->css_class : 'circ_standard_bucket');
        $this->set('containerStyle', $containerStyle);
        $this->set('copyStyle', $copyStyle);
        $this->set('copy', $this->record->copy);
    }
}
