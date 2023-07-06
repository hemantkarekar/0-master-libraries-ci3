<?php
class Purify extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }


    /**
     * retouch_image
     * @version 0.1.1
     * @return void
     * 
     * Resizes image with Defined Quality.
     * Works only for .jpg, .jpeg & .png images
     */
    function retouch_image()
    {
        $this->load->library('image_lib');

        $sourceFile = FCPATH . "assets/image.jpg"; // Assuming the image.jpg is in the "assets" folder
        $outputFile = FCPATH . "assets/compressed-image"; // Output file path
        $outputQuality = 60;

        // Configuration for JPEG
        $config['image_library'] = 'gd2';
        $config['source_image'] = $sourceFile;
        $config['quality'] = $outputQuality;
        $config['maintain_ratio'] = true;
        $config['height'] = 300;
        $config['new_image'] = $outputFile . ".jpg";

        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {
            // Handle resize error
            echo $this->image_lib->display_errors();
        }
        $this->image_lib->clear();

        // Configuration for WebP
        $config['image_library'] = 'gd2';
        $config['source_image'] = $sourceFile;
        $config['quality'] = $outputQuality;
        $config['create_thumb'] = false;
        $config['maintain_ratio'] = true;
        $config['height'] = 300;
        $config['new_image'] = $outputFile . ".webp";
        $config['file_ext'] = '.webp';

        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {
            // Handle resize error
            echo $this->image_lib->display_errors();
        }
        $this->image_lib->clear();
    }

    public function crop_image()
    {
        $this->load->library('image_lib');

        $sourceFile = FCPATH . "assets/compressed-image.jpg"; // Assuming the image.jpg is in the "assets" folder
        $outputFile = FCPATH . "assets/compressed-cropped-image"; // Output file path

        $config['image_library'] = 'gd2';
        $config['source_image'] = $sourceFile; // Replace with the actual source image path
        $config['new_image'] = $outputFile . ".jpg";
        $config['maintain_ratio'] = FALSE;

        $image_info = getimagesize($config['source_image']);
        $original_width = $image_info[0];
        $original_height = $image_info[1];
        $x_axis = 0; 
        $y_axis = 0; 
        
        if($original_height > $original_width){
            // Portrait Image set X
            $crop_width = $original_width;
            $crop_height = $crop_width;
            $y_axis = ($original_height - $crop_height) / 2; // Calculate the y-axis coordinate for center cropping
        } else {
            $crop_height = $original_height;
            $crop_width = $crop_height;
            $x_axis = ($original_width - $crop_width) / 2; // Calculate the x-axis coordinate for center cropping
        }
        

        $config['width'] = $crop_width;
        $config['height'] = $crop_height;
        $config['x_axis'] = $x_axis;
        $config['y_axis'] = $y_axis;

        $this->image_lib->initialize($config);

        if (!$this->image_lib->crop()) {
            // Handle crop error
            echo $this->image_lib->display_errors();
        }

        $this->image_lib->clear();
    }
}
