<?php

class sfImageCre8CropImageMagick extends sfImageCropImageMagick 
{
  
  protected $ratio = 1;
  
  public function __construct($ratio=1)
  {
    $this->ratio = round($ratio, 1);
  }
  
  protected function setUp(sfImage $image)
  {
    $source_aspect_ratio = round($image->getWidth() / $image->getHeight(), 1);
    $desired_aspect_ratio = $this->ratio;
    
    if ( $source_aspect_ratio > $desired_aspect_ratio )
    {
      //
      // Triggered when source image is wider
      //
      $temp_height = $image->getHeight();
      $temp_width = ( int ) ( $image->getHeight() * $this->ratio );
    }
    elseif( $source_aspect_ratio == $desired_aspect_ratio )
    {
      $temp_height = $image->getHeight();
      $temp_width = $image->getWidth(); 
    }
    else
    {
      //
      // Triggered otherwise (i.e. source image is similar or taller)
      //
      $temp_width = $image->getWidth();
      $temp_height = ( int ) ( $image->getWidth() / $source_aspect_ratio );
    }
    
    $x0 = (int) ( $image->getWidth() - $temp_width ) / 2;
    $y0 = (int) ( $image->getHeight() - $temp_height ) / 2;
    
    $this->setWidth($temp_width);
    $this->setHeight($temp_height);
    $this->setLeft($x0);
    $this->setTop($y0);
    
  }
  
  /**
   * Apply the transform to the sfImage object.
   *
   * @access protected
   * @param sfImage
   * @return sfImage
   */
  protected function transform(sfImage $image)
  {
    $this->setUp($image);
    $resource = $image->getAdapter()->getHolder();
    $resource->cropImage($this->getWidth(), $this->getHeight(), $this->getLeft(), $this->getTop());
    
    return $image;
  }
  
}